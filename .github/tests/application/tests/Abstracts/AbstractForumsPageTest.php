<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\ForumsPage;
use Symfony\Component\BrowserKit\HttpBrowser;

abstract class AbstractForumsPageTest extends AbstractHttpTestCase
{
    protected Post $forumsPage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->forumsPage = ForumsPage::get();
    }

    public function testForumsPageAsGuest(): void
    {
        $this->browsers->guest->followRedirects(false);
        $this->assertForumsPageAccessible($this->browsers->guest);
    }

    public function testForumsPageAsAdmin(): void
    {
        $this->browsers->admin->followRedirects(false);
        $this->assertForumsPageAccessible($this->browsers->admin);
    }

    protected function assertForumsPageAccessible(HttpBrowser $browser): void
    {
        $crawler = $browser->request('GET', '/forums/');

        $this->assertPageStatusIs200($browser->getResponse());

        $this->assertEquals(
            $this->forumsPage->getTitle(),
            $crawler->filterXPath('//html/body/div[@id="page"]//article/header/h1')->innerText()
        );

        $this->assertStringContainsStringIgnoringCase(
            'No forums were found',
            $crawler->filterXPath('//html/body/div[@id="page"]//article//div[@class="bbp-template-notice"]')->text()
        );
    }
}
