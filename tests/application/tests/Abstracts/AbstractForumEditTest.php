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
        $this->assertForumEditPageRedirect($browser, $forum);
        $this->_testForumEditPlainPermalinkAsGuest($browser, $forum);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function _testForumEditAsAdmin(HttpBrowser $browser, Forum $forum): void
    {
        $crawler = $this->requestEditPage($browser, $forum);
        $this->assertForumEditPage($browser, $forum, $crawler);
        $this->_testForumEditPlainPermalinkAsAdmin($browser, $forum);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function _testForumSubmitEditAsAdmin(HttpBrowser $browser, Forum $forum, Forum $newForum): void
    {
        $crawler = $this->requestEditPage($browser, $forum);

        FrontendUtilities::submitEditForm($browser, $crawler, $newForum);

        $this->assertForumEditPageRedirect($browser, $forum);

        $crawler2 = $this->requestEditPage($browser, $forum);

        $this->assertForumEditPage($browser, $newForum, $crawler2);

        // Rollback to the original content
        FrontendUtilities::submitEditForm($browser, $crawler2, $forum);
    }

    protected function _testForumSubmitEditPlainAsAdmin(HttpBrowser $browser, Forum $forum, Forum $newForum): void
    {
        $crawler = $this->requestEditPagePlain($browser, $forum, false);

        FrontendUtilities::submitEditForm($browser, $crawler, $newForum);

        $this->assertForumEditPageRedirect($browser, $forum);

        $crawler2 = $this->requestEditPagePlain($browser, $forum, false);

        $this->assertForumEditPage($browser, $newForum, $crawler2);

        // Rollback to the original content
        FrontendUtilities::submitEditForm($browser, $crawler2, $forum);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function _testForumEditPlainPermalinkAsGuest(HttpBrowser $browser, Forum $forum): void
    {
        $browser->request('GET', URL::editPlainPermalink($this->browsers->getHomePageURL(), $forum, true));

        $this->assertForumEditPageRedirect($browser, $forum);

        $browser->request('GET', URL::editPlainPermalink($this->browsers->getHomePageURL(), $forum, false));

        $this->assertForumEditPageRedirect($browser, $forum);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function _testForumEditPlainPermalinkAsAdmin(HttpBrowser $browser, Forum $forum): void
    {
        $this->requestEditPagePlain($browser, $forum, true);
        $this->assertForumEditPageRedirectAsAdmin($browser, $forum);

        $crawler = $this->requestEditPagePlain($browser, $forum, false);
        $this->assertForumEditPage($browser, $forum, $crawler);
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
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function requestEditPagePlain(HttpBrowser $browser, Forum $forum, bool $useNumericPermalinksRequests): Crawler
    {
        $browser->followRedirects(false);

        return $browser->request(
            'GET',
            URL::editPlainPermalink(
                $this->browsers->getHomePageURL(),
                $forum,
                $useNumericPermalinksRequests
            )
        );
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    private function assertForumEditPage(HttpBrowser $browser, Forum $forum, Crawler $crawler): void
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
    private function assertForumEditPageRedirect(HttpBrowser $browser, Forum $forum): void
    {
        $this->assertIsRedirect($browser->getResponse());
        $this->assertLocation($this->useNumericPermalinksHTML ? $forum->getNumericPermalink() : $forum->getSamplePermalink(), $browser->getResponse());
    }

    private function assertForumEditPageRedirectAsAdmin(HttpBrowser $browser, Forum $forum): void
    {
        $this->assertIsRedirect($browser->getResponse());
        $this->assertLocation(URL::editPermalinkEditAsPlain($forum, $this->useNumericPermalinksHTML), $browser->getResponse());
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
