<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Page;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\ForumsPage;

abstract class AbstractForumsPageTest extends AbstractHttpTestCase
{
    protected Page $forumsPage;

    /**
     * @throws \Random\RandomException
     */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->forumsPage = ForumsPage::get();
    }

    public function testForumsPageAsGuest(): void
    {
        $this->requestForumsPage($this->browsers->guest);
        $this->assertForumsPageAccessible($this->browsers->guest);
    }

    public function testForumsPageAsAdmin(): void
    {
        $this->requestForumsPage($this->browsers->admin);
        $this->assertForumsPageAccessible($this->browsers->admin);
    }

    protected function requestForumsPage(HttpBrowser $browser): void
    {
        $browser->followRedirects(false);
        $browser->request('GET', '/forums/');
    }

    protected function assertForumsPageAccessible(HttpBrowser $browser): void
    {
        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($this->forumsPage->getTitle(), $browser->getCrawler());
    }

    protected function assertForumsPageHasNoForums(HttpBrowser $browser): void
    {
        $this->assertPageContainsNotice('No forums were found', $browser->getCrawler());
    }
}
