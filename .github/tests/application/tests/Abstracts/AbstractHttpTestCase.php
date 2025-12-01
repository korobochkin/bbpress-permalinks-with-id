<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\AbstractPost;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowsersService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractHttpTestCase extends TestCase
{
    protected BrowsersService $browsers {
        get {
            if (isset($GLOBALS['BROWSERS_SERVICE'])
                && is_a($GLOBALS['BROWSERS_SERVICE'], BrowsersService::class)
            ) {
                return $GLOBALS['BROWSERS_SERVICE'];
            }

            throw new \RuntimeException('BROWSERS_SERVICE not found in $GLOBALS. Ensure phpunit-bootstrap.php is loaded.');
        }
    }

    protected bool $useNumericPermalinksRequests = false;

    protected bool $useNumericPermalinksHTML = false;

    protected function assertPageStatusIs200(Response $response): void
    {
        $this->assertSame(200, $response->getStatusCode());
    }

    protected function assertPageStatusIs200OrRedirect(Response $response): void
    {
        $this->assertThat(
            $response->getStatusCode(),
            $this->logicalOr(
                $this->logicalAnd(
                    $this->greaterThanOrEqual(300),
                    $this->lessThan(400),
                ),
                $this->equalTo(200),
            ),
        );
    }

    protected function assertIsRedirect(Response $response): void
    {
        $this->assertThat($response->getStatusCode(), $this->logicalAnd($this->greaterThanOrEqual(300), $this->lessThan(400)));
    }

    protected function assertSampleLinkIsOk(AbstractPost $post): void
    {
        $this->assertThat(
            $post->getSamplePermalink(),
            $this->logicalAnd(
                $this->stringStartsWith('http://'),
                $this->stringContains($post->getType()->value),
                $this->logicalOr(
                    $this->stringContains((string) $post->getId()),
                    $this->stringContains($post->getName()),
                ),
            ),
        );
    }

    protected function assertPageTitleEquals(string $expected, Crawler $crawler): void
    {
        $this->assertEquals(
            $expected,
            $crawler->filterXPath('//html/body/div[@id="page"]//article/header/h1')->innerText(),
        );
    }

    protected function assertBbPressBreadCrumbsContains(string $needle, Crawler $crawler): void
    {
        $this->assertStringContainsString(
            $needle,
            $crawler->filterXPath('//html/body/div[@id="page"]//article//div[contains(@class, "bbp-breadcrumb")]')->text(),
        );
    }

    protected function assertPageContainsNotice(string $needle, Crawler $pageCrawler): void
    {
        $noticeCrawler = $pageCrawler->filterXPath('//html/body/div[@id="page"]//article//div[contains(@class, "bbp-template-notice")]');

        $noticesOnPage = [];
        foreach ($noticeCrawler as $node) {
            $noticesOnPage[] = trim($node->textContent);
        }

        $this->assertStringContainsStringIgnoringCase($needle, implode(PHP_EOL, $noticesOnPage));
    }

    protected function assertBbPressReplyContentContains(Reply|Topic $post, Crawler $crawler): void
    {
        $this->assertStringContainsString(
            $post->getContent(),
            $crawler->filterXPath('//html/body/div[@id="page"]//article//div[contains(@class, "entry-content")]//div[contains(@class, "bbp-reply-content")]/p')->text(),
        );
    }

    protected function assertBbPressTopicHasLink(Topic $topic, Crawler $crawler): void
    {
        $link = $this->getReplyPermalink($crawler);
        $this->assertStringStartsWith(
            $this->useNumericPermalinksHTML ? $topic->getNumericPermalink() : $topic->getSamplePermalink(),
            $link->attr('href'),
        );
        $this->assertSame(
            '#'.$topic->getId(),
            $link->text(),
        );
    }

    protected function assertBbPressReplyHasLink(Topic $topic, Reply $reply, Crawler $crawler): void
    {
        $link = $this->getReplyPermalink($crawler);
        $this->assertStringStartsWith(
            $this->useNumericPermalinksHTML ? $topic->getNumericPermalink() : $topic->getSamplePermalink(),
            $link->attr('href'),
        );
        $this->assertStringEndsWith(
            (string) $reply->getId(),
            $link->attr('href'),
        );
        $this->assertSame(
            '#'.$reply->getId(),
            $link->text(),
        );
    }

    private function getReplyPermalink(Crawler $crawler): Crawler
    {
        return $crawler->filterXPath('//html/body/div[@id="page"]//article//div[contains(@class, "entry-content")]//div[contains(@class, "bbp-reply-header")]//a[contains(@class, "bbp-reply-permalink")]');
    }
}
