<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Browser\FrontendUtilities;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\URL;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractForumEditTest extends AbstractHttpTestCase
{
    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function _testForumEditAsGuest(HttpBrowser $browser, Forum $forum): void
    {
        $this->requestEditPage($browser, $forum);
        $this->assertEditPageRedirected($browser, $forum);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function _testForumEditAsAdmin(HttpBrowser $browser, Forum $forum): void
    {
        $crawler = $this->requestEditPage($browser, $forum);
        $this->testForumEditPage($browser, $forum, $crawler);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function _testForumSubmitEditAsAdmin(HttpBrowser $browser, Forum $forum, Forum $newForum): void
    {
        $crawler = $this->requestEditPage($browser, $forum);

        FrontendUtilities::submitEditForm($browser, $crawler, $newForum);

        $this->assertEditPageRedirected($browser, $forum);

        $crawler2 = $this->requestEditPage($browser, $forum);

        $this->testForumEditPage($browser, $newForum, $crawler2);

        // Rollback to the original content
        FrontendUtilities::submitEditForm($browser, $crawler2, $forum);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    private function requestEditPage(HttpBrowser $browser, Forum $forum): Crawler
    {
        $browser->followRedirects(false);

        return $browser->request('GET', URL::editPermalink($forum, $this->useNumericPermalinksRequests));
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    private function testForumEditPage(HttpBrowser $browser, Forum $forum, Crawler $crawler): void
    {
        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);

        $this->assertForumEditFormHasId($forum, $crawler);
        $this->assertForumEditFormHasTitle($forum, $crawler);
        $this->assertForumEditFormHasContent($forum, $crawler);
        $this->assertForumEditFormHasSubmit($crawler);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    private function assertEditPageRedirected(HttpBrowser $browser, Forum $forum): void
    {
        $this->assertIsRedirect($browser->getResponse());
        $this->assertLocation($this->useNumericPermalinksHTML ? $forum->getNumericPermalink() : $forum->getSamplePermalink(), $browser->getResponse());
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    private function assertForumEditFormHasId(Forum $forum, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//input[@type="hidden" and @name="bbp_forum_id"]');

        $this->assertCount(1, $input);
        $this->assertEquals($forum->getId(), $input->attr('value'));
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    private function assertForumEditFormHasTitle(Forum $forum, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//input[@name="bbp_forum_title"]');

        $this->assertCount(1, $input);
        $this->assertEquals($forum->getTitle(), $input->attr('value'));
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    private function assertForumEditFormHasContent(Forum $forum, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//textarea[@name="bbp_forum_content"]');

        $this->assertCount(1, $input);
        $this->assertEquals($forum->getContent(), $input->innerText());
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    private function assertForumEditFormHasSubmit(Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//button[@name="bbp_forum_submit"]');

        $this->assertCount(1, $input);
        $this->assertEquals('Submit', $input->text());
    }
}
