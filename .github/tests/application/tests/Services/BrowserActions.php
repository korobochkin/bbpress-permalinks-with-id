<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\BbPressPostInterface;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\PostInterface;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Page;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Status;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Symfony\Component\DomCrawler\Crawler;

final class BrowserActions
{
    public static function createPostViaWPAdmin(HttpBrowser $browser, Forum|Page|Reply|Topic $post): Crawler
    {
        if ($post instanceof Page) {
            return self::createNativePostTypes($browser, $post);
        }

        return self::createBbPressPostTypes($browser, $post);
    }

    private static function createNativePostTypes(HttpBrowser $browser, Page $post): Crawler
    {
        $crawler = self::requestPostNewPage($browser, $post);

        $nonce = $crawler->filterXPath('//form//input[(@id="_wpnonce" or @name="_wpnonce") and @type="hidden"]');

        // In WordPress 5.9.3 "//form/input//[...] doesn't work. Probably because some markup are invalid.
        $postID = self::getPostId($crawler);

        $postType = $crawler->filterXPath('//form//input[(@id="post_type" or @name="post_type") and @type="hidden"]');
        $action = $crawler->filterXPath('//form//input[(@id="hiddenaction" or @name="action") and @type="hidden"]');
        $originalAction = $crawler->filterXPath('//form//input[(@id="originalaction" or @name="originalaction") and @type="hidden"]');
        $userID = self::getUserId($crawler);
        $originalPostStatus = $crawler->filterXPath('//form//input[(@id="original_post_status" or @name="original_post_status") and @type="hidden"]');
        $referredBy = $crawler->filterXPath('//form//input[(@id="referredby" or @name="referredby") and @type="hidden"]');

        // XPath for a form element with all required fields: //form[input[(@id="_wpnonce" or @name="_wpnonce") and @type="hidden"]]

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

    private static function createBbPressPostTypes(HttpBrowser $browser, BbPressPostInterface $post): Crawler
    {
        $crawler = self::requestPostNewPage($browser, $post);

        $form = $crawler->filterXPath('//body//div[@id="wpbody"]//input[@id="publish"]')->form();

        $post->setId((int) self::getPostId($crawler)->attr('value'));
        $post->setAuthorId((int) self::getUserId($crawler)->attr('value'));

        $formData = [
            'post_title' => $post->getTitle(),
            'content' => $post->getContent(),
            'post_name' => $post->getName(),
        ];

        if ($post instanceof Topic) {
            $formData['parent_id'] = $post->getParentForumId();
        } elseif ($post instanceof Reply) {
            if ($form->has('bbp_forum_id')) {
                // For bbPress 2.5. Newer versions 2.6 do not have this field.
                $formData['bbp_forum_id'] = $post->getParentForumId();
            }
            $formData['parent_id'] = $post->getParentTopicId();
        }

        $savedPostCrawler = $browser->submit($form, $formData);

        $post->setStatus(self::getPostStatus($savedPostCrawler));
        $post->setSamplePermalink(self::getSamplePermalink($savedPostCrawler));

        return $savedPostCrawler;
    }

    private static function requestPostNewPage(HttpBrowser $browser, PostInterface $post): Crawler
    {
        return $browser->request('GET', '/wp-admin/post-new.php?post_type='.$post->getType()->value);
    }

    private static function getPostId(Crawler $crawler): Crawler
    {
        // In WordPress 5.9.3 "//form/input//[...] doesn't work. Probably because some markup are invalid.
        return $crawler->filterXPath('//input[(@id="post_ID" or @name="post_ID") and @type="hidden"]');
    }

    private static function getUserId(Crawler $crawler): Crawler
    {
        return $crawler->filterXPath('//form//input[(@id="user-id" or @name="user_ID") and @type="hidden"]');
    }

    private static function getPostStatus(Crawler $crawler): Status
    {
        return Status::from(
            $crawler->filterXPath('//form//input[(@id="original_post_status" or @name="original_post_status") and @type="hidden"]')->attr('value')
        );
    }

    private static function getSamplePermalink(Crawler $crawler): string
    {
        return $crawler->filterXPath('//body//*[contains(@id, "sample-permalink")]//a')->attr('href');
    }
}
