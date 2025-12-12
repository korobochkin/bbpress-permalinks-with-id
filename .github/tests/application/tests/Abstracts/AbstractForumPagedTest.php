<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\URL;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractForumPagedTest extends AbstractHttpTestCase
{
    /**
     * @param Topic[] $topics
     */
    protected function _testForumPaged(HttpBrowser $browser, Forum $forum, int $page, array $topics): void
    {
        $browser->followRedirects(false);
        $permalink = URL::pagePermalink($forum, $page, $this->useNumericPermalinksRequests);
        $crawler = $browser->request('GET', $permalink);

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);

        $this->assertThat(
            $this->buildTitlesOnPageList($crawler),
            $this->logicalAnd(...$this->buildConstraintsForTopicTitles($topics)),
        );
    }

    protected function getTopicsTitlesList(Crawler $crawler): Crawler
    {
        return $crawler->filterXPath('//body//div[@class="site-content"]//article//div[contains(@class, "entry-content")]//*[contains(@class, "bbp-topics")]//*[contains(@class, "bbp-topic-title")]/a');
    }

    protected function buildTitlesOnPageList(Crawler $crawler): array
    {
        $titlesOnPage = [];
        foreach ($this->getTopicsTitlesList($crawler) as $topicTitleLink) {
            $titlesOnPage[] = $topicTitleLink->textContent;
        }

        return $titlesOnPage;
    }

    protected function buildConstraintsForTopicTitles(array $topics): array
    {
        $constraints = [];
        foreach ($topics as $topic) {
            $constraints[] = $this->containsEqual($topic->getTitle());
        }

        return $constraints;
    }
}
