<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractForumsTest extends AbstractHttpTestCase
{
    protected bool $forumsAreEmpty = true;

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
        $crawler = $browser->request('GET', $this->useNumericPermalinks ? $forum->getNumericPermalink() : $forum->getSamplePermalink());

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);

        if ($this->forumsAreEmpty) {
            $this->assertPageContainsNotice('This forum is empty', $crawler);
            $this->assertPageContainsNotice('No topics were found here', $crawler);
        } else {
            $this->assertPageContainsNotice('This forum contains', $crawler);
            $this->assertPageContainsNotice('and was last updated by', $crawler);
        }

        return $crawler;
    }

    protected function testNotLoggedIn(Crawler $crawler): void
    {
        $this->assertPageContainsNotice('You must be logged in', $crawler);
    }
}
