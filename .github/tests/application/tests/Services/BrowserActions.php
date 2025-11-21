<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\AbstractPost;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Page;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Status;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Type;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class BrowserActions
{
    public static function createPostViaWPAdmin(HttpBrowser $browser, AbstractPost $post): Crawler
    {
        return match ($post->getType()) {
            Type::Page, Type::Post => self::createNativePostTypes($browser, $post),
            Type::Forum, Type::Topic, Type::Reply => self::createBbPressPostTypes($browser, $post),
            default => throw new \LogicException('Not supported post type'),
        };
    }

    private static function createNativePostTypes(HttpBrowser $browser, Page $post): Crawler
    {
        $crawler = self::requestPostNewPage($browser, $post);

        $nonce = $crawler->filterXPath('//form//input[(@id="_wpnonce" or name="_wp_nonce") and @type="hidden"]');

        // In WordPress 5.9.3 "//form/input//[...] doesn't work. Probably because some markup are invalid.
        $postID = self::getPostId($crawler);

        $postType = $crawler->filterXPath('//form//input[(@id="post_type" or name="post_type") and @type="hidden"]');
        $action = $crawler->filterXPath('//form//input[(@id="hiddenaction" or name="action") and @type="hidden"]');
        $originalAction = $crawler->filterXPath('//form//input[(@id="originalaction" or name="originalaction") and @type="hidden"]');
        $userID = self::getUserId($crawler);
        $originalPostStatus = $crawler->filterXPath('//form//input[(@id="original_post_status" or name="original_post_status") and @type="hidden"]');
        $referredBy = $crawler->filterXPath('//form//input[(@id="referredby" or name="referredby") and @type="hidden"]');

        // XPath for a form element with all required fields: //form[input[(@id="_wpnonce" or @name="_wp_nonce") and @type="hidden"]]

        $post->setId((int) $postID->attr('value'));
        $post->setAuthorId((int) $userID->attr('value'));

        return $browser->request(
            'POST',
            '/wp-admin/post.php',
            [
                '_wpnonce' => $nonce->attr('value'),
                '_wp_http_referer' => '/wp-admin/post-new.php?post_type='.$post->getType()->value,
                'action' => $action->attr('value'),
                'originalaction' => $originalAction->attr('value'),
                'post_author' => $userID->attr('value'),
                'post_type' => $postType->attr('value'),
                'original_post_status' => $originalPostStatus->attr('value'),
                'referredby' => $referredBy->attr('value'),
                'post_ID' => $postID->attr('value'),
                'post_title' => $post->getTitle(),
                'content' => $post->getContent(),
                'post_status' => $post->getStatus()->value, // 'draft'
                'post_name' => $post->getName(), // slug
            ],
        );
    }

    private static function createBbPressPostTypes(HttpBrowser $browser, Forum|Topic $post): Crawler
    {
        $crawler = self::requestPostNewPage($browser, $post);

        $form = $crawler->selectButton('Publish')->form();

        $post->setId((int) self::getPostId($crawler)->attr('value'));
        $post->setAuthorId((int) self::getUserId($crawler)->attr('value'));

        $formData = [
            'post_title' => $post->getTitle(),
            'content' => $post->getContent(),
            'post_name' => $post->getName(),
            ...(
                Type::Topic === $post->getType()
                ? [
                    'parent_id' => $post->getParentForumId(),
                ]
                : []
            ),
        ];

        //		switch ($post->getType()) {
        //
        //			case Type::Reply:
        // //				$formData[]
        //		}

        $savedPostCrawler = $browser->submit($form, $formData);

        $post->setStatus(Status::from(self::getPostStatus($savedPostCrawler)->attr('value')));

        return $savedPostCrawler;
    }

    private static function requestPostNewPage(HttpBrowser $browser, AbstractPost $post): Crawler
    {
        return $browser->request('GET', '/wp-admin/post-new.php?post_type='.$post->getType()->value);
    }

    private static function getPostId(Crawler $crawler): Crawler
    {
        // In WordPress 5.9.3 "//form/input//[...] doesn't work. Probably because some markup are invalid.
        return $crawler->filterXPath('//input[(@id="post_ID" or name="post_ID") and @type="hidden"]');
    }

    private static function getUserId(Crawler $crawler): Crawler
    {
        return $crawler->filterXPath('//form//input[(@id="user-id" or name="user_ID") and @type="hidden"]');
    }

    private static function getPostStatus(Crawler $crawler): Crawler
    {
        return $crawler->filterXPath('//form//input[(@id="original_post_status" or name="original_post_status") and @type="hidden"]');
    }
}
