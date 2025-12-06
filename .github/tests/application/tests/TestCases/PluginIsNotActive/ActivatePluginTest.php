<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractHttpTestCase;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class ActivatePluginTest extends AbstractHttpTestCase
{
    #[Attributes\DependsOnClass(TopicsPagedTest::class)]
    public function testActivatePlugin(): void
    {
        $this->browsers->admin->followRedirects(true);

        $link = $this->browsers->admin
            ->request('GET', '/wp-admin/plugins.php')
            ->filterXPath('//html/body//tr[@data-slug="bbpress-permalinks-with-id"]')
            ->selectLink('Activate')
            ->link()
        ;

        $this->assertPageStatusIs200($this->browsers->admin->getResponse());

        $crawler = $this->browsers->admin->click($link);

        $this->assertPageStatusIs200($this->browsers->admin->getResponse());

        $this->assertStringContainsStringIgnoringCase(
            'Plugin activated',
            $crawler->filterXPath('//html/body//div[contains(@class, "notice")]')->text(),
        );
    }

    #[Attributes\Depends('testActivatePlugin')]
    public function testFlushRewriteRules(): void
    {
        $this->browsers->admin->followRedirects(false);
        $this->browsers->admin->request('GET', '/wp-admin/options-permalink.php');

        $this->assertPageStatusIs200($this->browsers->admin->getResponse());
    }
}
