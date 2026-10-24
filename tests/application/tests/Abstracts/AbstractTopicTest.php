<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\URL;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractTopicTest extends AbstractHttpTestCase
{
    /**
     * @throws \InvalidArgumentException
     */
    public function testTopicAsGuest(Forum $forum, Topic $topic): void
    {
        $this->testTopic($this->browsers->guest, $forum, $topic);
        $this->testTopicPlain($this->browsers->guest, $forum, $topic);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function testTopicAsAdmin(Forum $forum, Topic $topic): void
    {
        $this->testTopic($this->browsers->admin, $forum, $topic);
        $this->testTopicPlain($this->browsers->admin, $forum, $topic);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function testTopic(HttpBrowser $browser, Forum $forum, Topic $topic): void
    {
        $browser->followRedirects(false);
        $crawler = $browser->request('GET', $this->useNumericPermalinksRequests ? $topic->getNumericPermalink() : $topic->getSamplePermalink());

        $this->assertTopic($browser->getResponse(), $crawler, $forum, $topic);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function testTopicPlain(HttpBrowser $browser, Forum $forum, Topic $topic): void
    {
        $browser->followRedirects(false);
        $crawler = $browser->request(
            'GET',
            $this->useNumericPermalinksRequests
                ? URL::getPlainNumericPermalink($this->browsers->getHomePageURL(), $topic)
                : URL::getPlainSlugPermalink($this->browsers->getHomePageURL(), $topic)
        );

        if ($this->useNumericPermalinksRequests) {
            $this->assertTopicRedirect($browser->getResponse(), $topic);
        } else {
            $this->assertTopic($browser->getResponse(), $crawler, $forum, $topic);
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function assertTopic(Response $response, Crawler $crawler, Forum $forum, Topic $topic): void
    {
        $this->assertPageStatusIs200($response);
        $this->assertPageTitleEquals($topic->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($topic->getTitle(), $crawler);
        $this->assertBbPressReplyContentContains($topic, $crawler);
        $this->assertBbPressTopicHasLink($topic, $crawler);
    }

    private function assertTopicRedirect(Response $response, Topic $topic): void
    {
        $this->assertIsRedirect($response);
        $this->assertLocation(
            $this->useNumericPermalinksHTML
                ? $topic->getNumericPermalink()
                : $topic->getSamplePermalink(),
            $response
        );
    }
}
