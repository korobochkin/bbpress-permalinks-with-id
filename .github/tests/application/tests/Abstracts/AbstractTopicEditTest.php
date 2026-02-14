<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Browser\FrontendUtilities;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\URL;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractTopicEditTest extends AbstractHttpTestCase
{
    /**
     * @throws \LogicException
     */
    protected function _testTopicEditAsGuest(HttpBrowser $browser, Forum $forum, Topic $topic): void
    {
        $this->requestEditPage($browser, $topic);
        $this->assertEditPageRedirected($browser, $topic);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function _testTopicEditAsAdmin(HttpBrowser $browser, Forum $forum, Topic $topic): void
    {
        $crawler = $this->requestEditPage($browser, $topic);
        $this->testTopicEditPage($browser, $forum, $topic, $crawler);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function _testTopicSubmitEditAsAdmin(HttpBrowser $browser, Forum $forum, Topic $topic, Topic $newTopic): void
    {
        $crawler = $this->requestEditPage($browser, $topic);

        FrontendUtilities::submitEditForm($browser, $crawler, $newTopic);

        $this->assertEditPageRedirected($browser, $topic);

        $crawler2 = $this->requestEditPage($browser, $topic);

        $this->testTopicEditPage($browser, $forum, $newTopic, $crawler2);

        // Rollback to the original content
        FrontendUtilities::submitEditForm($browser, $crawler2, $topic);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    private function requestEditPage(HttpBrowser $browser, Topic $topic): Crawler
    {
        $browser->followRedirects(false);

        return $browser->request('GET', URL::editPermalink($topic, $this->useNumericPermalinksRequests));
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function testTopicEditPage(HttpBrowser $browser, Forum $forum, Topic $topic, Crawler $crawler): void
    {
        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($topic->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($topic->getTitle(), $crawler);

        $this->assertTopicEditFormHasForumId($forum, $crawler);
        $this->assertTopicEditFormHasId($topic, $crawler);
        $this->assertTopicEditFormHasStick($crawler);
        $this->assertTopicEditFormHasTitle($topic, $crawler);
        $this->assertTopicEditFormHasContent($topic, $crawler);
        $this->assertTopicEditFormHasSubmit($crawler);
    }

    private function assertEditPageRedirected(HttpBrowser $browser, Topic $topic): void
    {
        $this->assertIsRedirect($browser->getResponse());
        $this->assertLocation($this->useNumericPermalinksHTML ? $topic->getNumericPermalink() : $topic->getSamplePermalink(), $browser->getResponse());
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function assertTopicEditFormHasForumId(Forum $forum, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//select[@name="bbp_forum_id"]/option[@selected="selected"]');

        $this->assertCount(1, $input);
        $this->assertEquals($forum->getId(), $input->attr('value'));
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function assertTopicEditFormHasId(Topic $topic, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//input[@type="hidden" and @name="bbp_topic_id"]');

        $this->assertCount(1, $input);
        $this->assertEquals($topic->getId(), $input->attr('value'));
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function assertTopicEditFormHasStick(Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//select[@name="bbp_stick_topic"]/option[@selected="selected"]');
        $this->assertCount(1, $input);
        $this->assertEquals('unstick', $input->attr('value'));
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function assertTopicEditFormHasTitle(Topic $topic, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//input[@name="bbp_topic_title"]');

        $this->assertCount(1, $input);
        $this->assertEquals($topic->getTitle(), $input->attr('value'));
    }

    private function assertTopicEditFormHasContent(Topic $topic, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//textarea[@name="bbp_topic_content"]');

        $this->assertCount(1, $input);
        $this->assertEquals($topic->getContent(), $input->innerText());
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function assertTopicEditFormHasSubmit(Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//button[@name="bbp_topic_submit"]');

        $this->assertCount(1, $input);
        $this->assertEquals('Submit', $input->text());
    }
}
