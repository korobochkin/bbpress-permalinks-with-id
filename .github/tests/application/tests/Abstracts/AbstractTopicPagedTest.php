<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\URL;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractTopicPagedTest extends AbstractHttpTestCase
{
    protected function _testTopicPaged(HttpBrowser $browser, Forum $forum, Topic $topic, int $page, array $replies): void
    {
        $browser->followRedirects(false);
        $permalink = URL::pagePermalink($topic, $page, $this->useNumericPermalinksRequests);
        $crawler = $browser->request('GET', $permalink);

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($topic->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($topic->getTitle(), $crawler);

        $this->assertThat(
            $contents = $this->buildReplyContentsOnPageList($crawler),
            $this->logicalAnd(...$constraints = $this->buildConstraintsForReplyContents($topic, $page, $replies)),
        );

        $this->assertSameSize($constraints, $contents);
    }

    protected function getReplyContentsList(Crawler $crawler): Crawler
    {
        return $crawler->filterXPath('//body//div[@class="site-content"]//article//div[contains(@class, "entry-content")]//div[contains(@class, "reply")]/div[contains(@class, "bbp-reply-content")]/p');
    }

    protected function buildReplyContentsOnPageList(Crawler $crawler): array
    {
        $contentsOnPage = [];
        foreach ($this->getReplyContentsList($crawler) as $replyContent) {
            $contentsOnPage[] = $replyContent->textContent;
        }

        return $contentsOnPage;
    }

    /**
     * @param Reply[] $replies
     */
    protected function buildConstraintsForReplyContents(Topic $topic, int $page, array $replies): array
    {
        $constraints = [];

        if (1 === $page) {
            $constraints[] = $this->containsEqual($topic->getContent());
        }

        foreach ($replies as $reply) {
            $constraints[] = $this->containsEqual($reply->getContent());
        }

        return $constraints;
    }
}
