<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Page;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;

abstract class AbstractForumsPageTest extends AbstractHttpTestCase
{
    public function testForumsPageAsGuest(Page $forumsPage): void
    {
        $this->requestForumsPage($this->browsers->guest);
        $this->assertForumsPage($this->browsers->guest, $forumsPage);
    }

    public function testForumsPageAsAdmin(Page $forumsPage): void
    {
        $this->requestForumsPage($this->browsers->admin);
        $this->assertForumsPage($this->browsers->admin, $forumsPage);
    }

    protected function requestForumsPage(HttpBrowser $browser): void
    {
        $browser->followRedirects(false);
        $browser->request('GET', '/forums/');
    }

    protected function assertForumsPage(HttpBrowser $browser, Page $forumsPage): void
    {
        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($forumsPage->getTitle(), $browser->getCrawler());
    }

    protected function assertForumsPageHasNoForums(HttpBrowser $browser): void
    {
        $this->assertPageContainsNotice('No forums were found', $browser->getCrawler());
    }
}
