<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractForumTest extends AbstractHttpTestCase
{
    protected bool $forumsAreEmpty = true;

    /**
     * @throws \InvalidArgumentException
     */
    public function testForumAsGuest(Forum $forum): void
    {
        $crawler = $this->testForum($this->browsers->guest, $forum);
        $this->testNotLoggedIn($crawler);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function testForumAsAdmin(Forum $forum): void
    {
        $this->testForum($this->browsers->admin, $forum);
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function testForum(HttpBrowser $browser, Forum $forum): Crawler
    {
        $browser->followRedirects(false);

        $this->testForumPlainPermalink($browser, $forum);

        $crawler = $browser->request('GET', $this->useNumericPermalinksRequests ? $forum->getNumericPermalink() : $forum->getSamplePermalink());

        $this->assertForumPage($forum, $browser->getResponse(), $crawler);

        return $crawler;
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function assertForumPage(Forum $forum, Response $response, Crawler $crawler): void
    {
        $this->assertPageStatusIs200($response);
        $this->assertPageTitleEquals($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);

        if ($this->forumsAreEmpty) {
            $this->assertPageContainsNotice('This forum is empty', $crawler);
            $this->assertPageContainsNotice('No topics were found here', $crawler);
        } else {
            $this->assertPageContainsNotice('This forum', $crawler);
            $this->assertPageContainsNotice('and was last updated', $crawler);
        }
    }

    protected function assertForumRedirect(Forum $forum, Response $response): void
    {
        $this->assertIsRedirect($response);
        $this->assertLocation(
            $this->useNumericPermalinksHTML
                ? $forum->getNumericPermalink() : $forum->getSamplePermalink(),
            $response
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function testForumPlainPermalink(HttpBrowser $browser, Forum $forum): void
    {
        $browser->request('GET', $forum->getPlainNumericPermalink($this->browsers->getHomePageURL()));

        $this->assertForumRedirect($forum, $browser->getResponse());

        $crawler = $browser->request('GET', $forum->getPlainSlugPermalink($this->browsers->getHomePageURL()));

        $this->assertForumPage($forum, $browser->getResponse(), $crawler);
    }

    protected function testNotLoggedIn(Crawler $crawler): void
    {
        $this->assertPageContainsNotice('You must be logged in', $crawler);
    }
}
