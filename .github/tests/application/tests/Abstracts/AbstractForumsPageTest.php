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
        $this->assertForumsPageAccessible($this->browsers->guest);
    }

    public function testForumsPageAsAdmin(): void
    {
        $this->assertForumsPageAccessible($this->browsers->admin);
    }

    protected function assertForumsPageAccessible(HttpBrowser $browser): void
    {
        $browser->followRedirects(false);
        $crawler = $browser->request('GET', '/forums/');

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($this->forumsPage->getTitle(), $crawler);
        $this->assertPageContainsNotice('No forums were found', $crawler);
    }
}
