<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\Common;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractHttpTestCase;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowserActions;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\ForumsPage;
use PHPUnit\Framework\Attributes;
use Symfony\Component\BrowserKit\HttpBrowser;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class HomePageTest extends AbstractHttpTestCase
{
    private Post $forumsPage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->forumsPage = ForumsPage::get();
    }

    public function testForumsPageCreation(): void
    {
        $this->browsers->admin->followRedirects(true);

        BrowserActions::createPostViaWPAdmin($this->browsers->admin, $this->forumsPage);

        $this->assertEquals(200, $this->browsers->admin->getResponse()->getStatusCode());
    }

    #[Attributes\Depends('testForumsPageCreation')]
    public function testForumsPageAsGuest(): void
    {
        $this->browsers->guest->followRedirects(false);
        $this->assertForumsPageAccessible($this->browsers->guest);
    }

    #[Attributes\Depends('testForumsPageAsGuest')]
    public function testForumsPageAsAdmin(): void
    {
        $this->browsers->admin->followRedirects(false);
        $this->assertForumsPageAccessible($this->browsers->admin);
    }

    private function assertForumsPageAccessible(HttpBrowser $browser): void
    {
        $crawler = $browser->request('GET', '/forums/');

        $this->assertPageStatusIs200($browser->getResponse());

        $this->assertEquals(
            $this->forumsPage->getTitle(),
            $crawler->filterXPath('//html/body/div[@id="page"]//article/header/h1')->innerText()
        );

        $this->assertStringContainsStringIgnoringCase(
            'No forums were found',
            $crawler->filterXPath('//html/body/div[@id="page"]//article//div[@class="bbp-template-notice"]/ul/li')->innerText()
        );
    }
}
