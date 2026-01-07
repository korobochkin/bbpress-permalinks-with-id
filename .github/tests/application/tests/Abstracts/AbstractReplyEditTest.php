<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\HttpBrowser;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Browser\FrontendUtilities;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\URL;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractReplyEditTest extends AbstractHttpTestCase
{
    protected function _testReplyEditAsGuest(HttpBrowser $browser, Topic $topic, Reply $reply): void
    {
        $this->requestEditPage($browser, $reply);
        $this->assertEditPageRedirected($browser, $topic, $reply);
    }

    protected function _testReplyEditAsAdmin(HttpBrowser $browser, Forum $forum, Topic $topic, Reply $reply): void
    {
        $crawler = $this->requestEditPage($browser, $reply);
        $this->testReplyEditPage($browser, $forum, $topic, $reply, $crawler);
    }

    protected function _testReplySubmitEditAsAdmin(HttpBrowser $browser, Forum $forum, Topic $topic, Reply $reply, Reply $newReply): void
    {
        $crawler = $this->requestEditPage($browser, $reply);

        FrontendUtilities::submitEditForm($browser, $crawler, $newReply);

        /*
         * bbPress doesn't have title field in frontend form for replies.
         * But after submitting a form, it erases the actual title in the DB.
         * After that bbPress starts using "Reply To: $TOPIC_TITLE" instead.
         *
         * @see FrontendUtilities::submitEditForm
         */
        $reply->setTitle('');
        $newReply->setTitle('');

        $this->assertEditPageRedirected($browser, $topic, $reply);

        $crawler2 = $this->requestEditPage($browser, $reply);

        $this->testReplyEditPage($browser, $forum, $topic, $newReply, $crawler2);

        // Rollback to the original content
        FrontendUtilities::submitEditForm($browser, $crawler2, $reply);
    }

    private function requestEditPage(HttpBrowser $browser, Reply $reply): Crawler
    {
        $browser->followRedirects(false);

        return $browser->request('GET', URL::editPermalink($reply, $this->useNumericPermalinksRequests));
    }

    private function testReplyEditPage(HttpBrowser $browser, Forum $forum, Topic $topic, Reply $reply, Crawler $crawler): void
    {
        $expectedReplyTitle = $reply->getTitle();

        if ($expectedReplyTitle) {
            $expectedReplyTitle = 'Reply To: '.$topic->getTitle();
        }

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($expectedReplyTitle, $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($topic->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($expectedReplyTitle, $crawler);

        $this->assertReplyEditFormHasId($reply, $crawler);
        $this->assertReplyEditFormHasContent($reply, $crawler);
        $this->assertReplyEditFormHasSubmit($crawler);
    }

    private function assertEditPageRedirected(HttpBrowser $browser, Topic $topic, Reply $reply): void
    {
        $this->assertIsRedirect($browser->getResponse());

        $this->assertThat(
            $browser->getResponse()->getHeader('location'),
            $this->logicalOr(
                $this->equalTo(URL::replyAnchoredPermalink($topic, $reply, $this->useNumericPermalinksHTML)),
                $this->logicalAnd(
                    $this->stringStartsWith($this->useNumericPermalinksHTML ? $topic->getNumericPermalink() : $topic->getSamplePermalink()),
                    $this->stringEndsWith(URL::replyAnchor($reply)),
                ),
            ),
        );
    }

    private function assertReplyEditFormHasId(Reply $reply, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//input[@type="hidden" and @name="bbp_reply_id"]');

        $this->assertCount(1, $input);
        $this->assertEquals($reply->getId(), $input->attr('value'));
    }

    private function assertReplyEditFormHasContent(Reply $reply, Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//textarea[@name="bbp_reply_content"]');

        $this->assertCount(1, $input);
        $this->assertEquals($reply->getContent(), $input->innerText());
    }

    private function assertReplyEditFormHasSubmit(Crawler $crawler): void
    {
        $input = $crawler->filterXPath('//body//div[contains(@class, "entry-content")]//form[@name="new-post"]//button[@name="bbp_reply_submit"]');

        $this->assertCount(1, $input);
        $this->assertEquals('Submit', $input->text());
    }
}
