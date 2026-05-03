<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractTopicTest extends AbstractHttpTestCase
{
    /**
     * @throws \InvalidArgumentException
     */
    public function testTopicAsGuest(Forum $forum, Topic $topic): void
    {
        $this->testTopic($this->browsers->guest, $forum, $topic);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function testTopicAsAdmin(Forum $forum, Topic $topic): void
    {
        $this->testTopic($this->browsers->admin, $forum, $topic);
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function testTopic(HttpBrowser $browser, Forum $forum, Topic $topic): Crawler
    {
        $browser->followRedirects(false);
        $crawler = $browser->request('GET', $this->useNumericPermalinksRequests ? $topic->getNumericPermalink() : $topic->getSamplePermalink());

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($topic->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($topic->getTitle(), $crawler);
        $this->assertBbPressReplyContentContains($topic, $crawler);
        $this->assertBbPressTopicHasLink($topic, $crawler);

        return $crawler;
    }
}
