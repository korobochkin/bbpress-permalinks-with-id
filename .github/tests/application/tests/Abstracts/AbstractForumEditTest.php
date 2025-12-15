<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\URL;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractForumEditTest extends AbstractHttpTestCase
{
    protected function _testForumEditAsGuest(HttpBrowser $browser, Forum $forum): void
    {
        $this->requestEditPage($browser, $forum);

        $this->assertIsRedirect($browser->getResponse());
        $this->assertLocation($this->useNumericPermalinksHTML ? $forum->getNumericPermalink() : $forum->getSamplePermalink(), $browser->getResponse());
    }

    protected function _testForumEditAsAdmin(HttpBrowser $browser, Forum $forum): void
    {
        $crawler = $this->requestEditPage($browser, $forum);

        $this->_testForumEditPage($browser, $forum, $crawler);
    }

    protected function _testForumEditPage(HttpBrowser $browser, Forum $forum, Crawler $crawler): void
    {
        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);

        $this->assertForumEditFormHasId($forum, $crawler);
        $this->assertForumEditFormHasTitle($forum, $crawler);
        $this->assertForumEditFormHasContent($forum, $crawler);
        $this->assertForumEditFormHasSubmit($crawler);
    }

    protected function assertForumEditFormHasId(Forum $forum, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//input[@type="hidden" and @name="bbp_forum_id"]');

        $this->assertCount(1, $input);
        $this->assertEquals($forum->getId(), $input->attr('value'));
    }

    protected function assertForumEditFormHasTitle(Forum $forum, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//input[@name="bbp_forum_title"]');

        $this->assertCount(1, $input);
        $this->assertEquals($forum->getTitle(), $input->attr('value'));
    }

    protected function assertForumEditFormHasContent(Forum $forum, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//textarea[@name="bbp_forum_content"]');

        $this->assertCount(1, $input);
        $this->assertEquals($forum->getContent(), $input->innerText());
    }

    protected function assertForumEditFormHasSubmit(Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//button[@name="bbp_forum_submit"]');

        $this->assertCount(1, $input);
        $this->assertEquals('Submit', $input->text());
    }
}
