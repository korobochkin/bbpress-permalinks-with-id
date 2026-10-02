<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Page;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Symfony\Component\BrowserKit\Response;

abstract class AbstractForumsPageTest extends AbstractHttpTestCase
{
    /**
     * @throws \LogicException
     */
    public function testForumsPageAsGuest(Page $forumsPage): void
    {
        $this->testForumsPagePlainPermalink($this->browsers->guest, $forumsPage);
        $this->requestForumsPage($this->browsers->guest);
        $this->assertForumsPage($this->browsers->guest, $forumsPage);
    }

    /**
     * @throws \LogicException
     */
    public function testForumsPageAsAdmin(Page $forumsPage): void
    {
        $this->testForumsPagePlainPermalink($this->browsers->admin, $forumsPage);
        $this->requestForumsPage($this->browsers->admin);
        $this->assertForumsPage($this->browsers->admin, $forumsPage);
    }

    protected function requestForumsPage(HttpBrowser $browser): void
    {
        $browser->followRedirects(false);
        $browser->request('GET', '/forums/');
    }

    /**
     * @throws \LogicException
     */
    protected function testForumsPagePlainPermalink(HttpBrowser $browser, Page $forumsPage): void
    {
        $browser->followRedirects(false);
        $browser->request('GET', $forumsPage->getPlainNumericPermalink($this->browsers->getHomePageURL()));
        $this->assertForumsPageRedirect($forumsPage, $browser->getResponse());
    }

    protected function assertForumsPage(HttpBrowser $browser, Page $forumsPage): void
    {
        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($forumsPage->getTitle(), $browser->getCrawler());
    }

    protected function assertForumsPageRedirect(Page $forumsPage, Response $response): void
    {
        $this->assertIsRedirect($response);
        $this->assertLocation(
            $this->useNumericPermalinksHTML
                ? $forumsPage->getNumericPermalink() : $forumsPage->getSamplePermalink(),
            $response
        );
    }

    protected function assertForumsPageHasNoForums(HttpBrowser $browser): void
    {
        $this->assertPageContainsNotice('No forums were found', $browser->getCrawler());
    }
}
