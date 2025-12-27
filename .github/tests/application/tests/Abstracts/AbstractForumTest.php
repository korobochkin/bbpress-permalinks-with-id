<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractForumTest extends AbstractHttpTestCase
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
        $crawler = $browser->request('GET', $this->useNumericPermalinksRequests ? $forum->getNumericPermalink() : $forum->getSamplePermalink());

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);

        if ($this->forumsAreEmpty) {
            $this->assertPageContainsNotice('This forum is empty', $crawler);
            $this->assertPageContainsNotice('No topics were found here', $crawler);
        } else {
            $this->assertPageContainsNotice('This forum', $crawler);
            $this->assertPageContainsNotice('and was last updated', $crawler);
        }

        return $crawler;
    }

    protected function testNotLoggedIn(Crawler $crawler): void
    {
        $this->assertPageContainsNotice('You must be logged in', $crawler);
    }
}
