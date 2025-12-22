<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Browser;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\AbstractPost;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

class FrontendUtilities
{
    public static function submitEditForm(HttpBrowser $browser, Crawler $crawler, AbstractPost $post): void
    {
        $browser->submit(
            static::findEditForm($crawler),
            [
                'bbp_forum_title' => $post->getTitle(),
                'bbp_forum_content' => $post->getContent(),
            ],
        );
    }

    private static function findEditForm(Crawler $crawler): Form
    {
        return $crawler->filterXPath('//body//div[@id="page"]//div[contains(@class, "entry-content")]//form[@id="new-post"]')->form();
    }
}
