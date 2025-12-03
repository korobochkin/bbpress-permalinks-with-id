<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractHttpTestCase;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use PHPUnit\Framework\Attributes;
use Symfony\Component\BrowserKit\HttpBrowser;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class ForumsPagedTest extends AbstractHttpTestCase
{
    /**
     * @param Topic[] $topics
     */
    #[Attributes\DependsOnClass(TopicsTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getForumsPaged')]
    public function testForumPagedAsGuest(Forum $forum, int $page, array $topics): void
    {
        $this->_testForumPaged($this->browsers->guest, $forum, $page, $topics);
    }

    /**
     * @param Topic[] $topics
     */
    protected function _testForumPaged(HttpBrowser $browser, Forum $forum, int $page, array $topics): void
    {
        $browser->followRedirects(false);
        $permalink = $this->useNumericPermalinksRequests ? $forum->getNumericPermalink() : $forum->getSamplePermalink();
        $permalinkPaged = $permalink.'page/'.$page.'/';
        $crawler = $browser->request('GET', $permalinkPaged);

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);

        $topicTitleList = $crawler->filterXPath('//body//div[@class="site-content"]//article//div[contains(@class, "entry-content")]//*[contains(@class, "bbp-topics")]//*[contains(@class, "bbp-topic-title")]/a');

        $titlesOnPage = [];
        foreach ($topicTitleList as $topicTitleLink) {
            $titlesOnPage[] = $topicTitleLink->textContent;
        }

        $constraints = [];
        foreach ($topics as $topic) {
            $constraints[] = $this->containsEqual($topic->getTitle());
        }

        $this->assertThat(
            $titlesOnPage,
            $this->logicalAnd(...$constraints),
        );
    }
}
