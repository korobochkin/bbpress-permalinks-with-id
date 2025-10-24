<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class BrowserActions
{
    public static function createPostViaWPAdmin(HttpBrowser $browser, Post $post): Crawler
    {
        $crawler = $browser->request('GET', '/wp-admin/post-new.php?post_type='.$post->getType()->value);

        $nonce = $crawler->filterXPath('//form//input[(@id="_wpnonce" or name="_wp_nonce") and @type="hidden"]');
        $postID = $crawler->filterXPath('//form//input[(@id="post_ID" or name="post_ID") and @type="hidden"]');
        $postType = $crawler->filterXPath('//form//input[(@id="post_type" or name="post_type") and @type="hidden"]');
        $action = $crawler->filterXPath('//form//input[(@id="hiddenaction" or name="action") and @type="hidden"]');
        $originalAction = $crawler->filterXPath('//form//input[(@id="originalaction" or name="originalaction") and @type="hidden"]');
        $userID = $crawler->filterXPath('//form//input[(@id="user-id" or name="user_ID") and @type="hidden"]');
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
                '_wp_http_referer' => '/wp-admin/post-new.php?post_type=post',
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
}
