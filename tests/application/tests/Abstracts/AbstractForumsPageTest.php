<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Page;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\URL;
use Symfony\Component\BrowserKit\Response;

abstract class AbstractForumsPageTest extends AbstractHttpTestCase
{
    /**
     * @throws \LogicException
     */
    public function testForumsPageAsGuest(Page $forumsPage): void
    {
        $this->testForumsPagePlainPermalink($this->browsers->guest, $forumsPage);
        $this->testForumsPage($this->browsers->guest, $forumsPage);
    }

    /**
     * @throws \LogicException
     */
    public function testForumsPageAsAdmin(Page $forumsPage): void
    {
        $this->testForumsPagePlainPermalink($this->browsers->admin, $forumsPage);
        $this->testForumsPage($this->browsers->admin, $forumsPage);
    }

    protected function testForumsPage(HttpBrowser $browser, Page $forumsPage): void
    {
        $browser->followRedirects(false);
        $browser->request('GET', '/forums/');
        $this->assertForumsPage($browser, $forumsPage);
    }

    /**
     * @throws \LogicException
     */
    protected function testForumsPagePlainPermalink(HttpBrowser $browser, Page $forumsPage): void
    {
        $browser->followRedirects(false);
        $browser->request('GET', URL::getPlainNumericPermalink($this->browsers->getHomePageURL(), $forumsPage));
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
