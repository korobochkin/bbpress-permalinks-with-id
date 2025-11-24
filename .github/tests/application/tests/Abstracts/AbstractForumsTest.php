<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractForumsTest extends AbstractHttpTestCase
{
    public function testForumAsGuest(Forum $forum): void
    {
        $crawler = $this->testForum($this->browsers->guest, $forum);
        $this->testNotLoggedIn($crawler);
    }

    public function testForumAsAdmin(Forum $forum): void
    {
        $this->testForum($this->browsers->admin, $forum);
    }

    protected function testForum(HttpBrowser $browser, Forum $forum): Crawler
    {
        $browser->followRedirects(false);
        $crawler = $browser->request('GET', $forum->getSamplePermalink());

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);
        $this->assertPageContainsNotice('This forum is empty', $crawler);
        $this->assertPageContainsNotice('No topics were found here', $crawler);

        return $crawler;
    }

    protected function testNotLoggedIn(Crawler $crawler): void
    {
        $this->assertPageContainsNotice('You must be logged in', $crawler);
    }
}
