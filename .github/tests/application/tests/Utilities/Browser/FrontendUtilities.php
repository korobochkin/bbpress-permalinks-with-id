<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Browser;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\BbPressPostInterface;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Type;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

class FrontendUtilities
{
    public static function submitEditForm(HttpBrowser $browser, Crawler $crawler, BbPressPostInterface $post): void
    {
        $form = static::findEditForm($crawler);
        $type = $post->getType()->value;

        $browser->submit(
            $form,
            [
                ...(
                    Type::Forum === $post->getType()
                    || Type::Topic === $post->getType()
                        ? ['bbp_'.$type.'_title' => $post->getTitle()]
                        : []
                ),
                'bbp_'.$type.'_content' => $post->getContent(),
            ],
        );
    }

    private static function findEditForm(Crawler $crawler): Form
    {
        return $crawler->filterXPath('//body//div[@id="page"]//div[contains(@class, "entry-content")]//form[@id="new-post"]')->form();
    }
}
