<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractReplyTest extends AbstractHttpTestCase
{
    /**
     * @throws \InvalidArgumentException
     */
    public function testReplyAsGuest(Forum $forum, Topic $topic, Reply $reply): void
    {
        $this->testReply($this->browsers->guest, $forum, $topic, $reply);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function testReplyAsAdmin(Forum $forum, Topic $topic, Reply $reply): void
    {
        $this->testReply($this->browsers->admin, $forum, $topic, $reply);
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function testReply(HttpBrowser $browser, Forum $forum, Topic $topic, Reply $reply): Crawler
    {
        $browser->followRedirects(false);
        $crawler = $browser->request('GET', $reply->getSamplePermalink());

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($reply->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($topic->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($reply->getTitle(), $crawler);
        $this->assertBbPressReplyContentContains($reply, $crawler);
        $this->assertBbPressReplyHasLink($topic, $reply, $crawler);

        return $crawler;
    }
}
