<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Browser;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\BbPressPostInterface;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

final class FrontendUtilities
{
    public static function submitEditForm(HttpBrowser $browser, Crawler $crawler, BbPressPostInterface $post): void
    {
        $form = self::findEditForm($crawler);
        $type = $post->getType()->value;

        $browser->submit(
            $form,
            [
                ...(
                    $post->getType()->hasTitle()
                    ? ['bbp_'.$type.'_title' => $post->getTitle()]
                    : []
                ),
                'bbp_'.$type.'_content' => $post->getContent(),
            ],
        );
    }

    private static function findEditForm(Crawler $crawler): Form
    {
        return $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]')->form();
    }
}
