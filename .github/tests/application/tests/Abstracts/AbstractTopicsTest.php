<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractTopicsTest extends AbstractHttpTestCase
{
    protected bool $useNumericPermalinks = false;

    public function testTopicAsGuest(Forum $forum, Topic $topic): void
    {
        $this->testTopic($this->browsers->guest, $forum, $topic);
    }

    public function testTopicAsAdmin(Forum $forum, Topic $topic): void
    {
        $this->testTopic($this->browsers->admin, $forum, $topic);
    }

    protected function testTopic(HttpBrowser $browser, Forum $forum, Topic $topic): Crawler
    {
        $browser->followRedirects(false);
        $crawler = $browser->request('GET', $this->useNumericPermalinks ? $topic->getNumericPermalink() : $topic->getSamplePermalink());

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($topic->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($topic->getTitle(), $crawler);
        $this->assertBbPressReplyContentContains($topic, $crawler);
        $this->assertBbPressTopicHasLink($topic, $crawler);

        return $crawler;
    }
}
