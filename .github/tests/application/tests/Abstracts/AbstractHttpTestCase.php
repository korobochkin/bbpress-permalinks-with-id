<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Logs\LogEntry;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\PostInterface;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowsersService;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\WordPressServerLogs;
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

    protected WordPressServerLogs $logs {
        get {
            if (isset($GLOBALS['WORDPRESS_LOGS_SERVICE'])
                && is_a($GLOBALS['WORDPRESS_LOGS_SERVICE'], WordPressServerLogs::class)) {
                return $GLOBALS['WORDPRESS_LOGS_SERVICE'];
            }

            throw new \RuntimeException('WORDPRESS_LOGS_SERVICE not found in $GLOBALS. Ensure phpunit-bootstrap.php is loaded.');
        }
    }

    protected bool $useNumericPermalinksRequests = false;

    protected bool $useNumericPermalinksHTML = false;

    /**
     * @throws \UnexpectedValueException
     */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->logs->checkpoint();
    }

    /**
     * @throws \UnexpectedValueException
     */
    #[\Override]
    protected function assertPostConditions(): void
    {
        parent::assertPostConditions();

        $newErrors = $this->logs->getErrorsSinceCheckpoint();

        if ([] !== $newErrors) {
            $messages = array_map(
                static fn (LogEntry $entry): string => $entry->message,
                $newErrors,
            );

            $this->fail(
                'WordPress server produced '.count($newErrors)." error(s) during this test:\n"
                .implode("\n", $messages),
            );
        }
    }

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

    protected function assertLocation(string $expectedLocation, Response $response): void
    {
        $this->assertEquals($expectedLocation, $response->getHeader('location'));
    }

    protected function assertSampleLinkIsOk(PostInterface $post): void
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

    /**
     * @throws \InvalidArgumentException
     */
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

    /**
     * @throws \InvalidArgumentException
     */
    protected function assertBbPressReplyContentContains(Reply|Topic $post, Crawler $crawler): void
    {
        $this->assertStringContainsString(
            $post->getContent(),
            $crawler->filterXPath('//html/body/div[@id="page"]//article//div[contains(@class, "entry-content")]//div[contains(@class, "bbp-reply-content")]/p')->text(),
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function assertBbPressTopicHasLink(Topic $topic, Crawler $crawler): void
    {
        $link = $this->getReplyPermalink($crawler);
        $this->assertStringStartsWith(
            $this->useNumericPermalinksHTML ? $topic->getNumericPermalink() : $topic->getSamplePermalink(),
            $link->attr('href') ?? throw new \RuntimeException(),
        );
        $this->assertSame(
            '#'.$topic->getId(),
            $link->text(),
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function assertBbPressReplyHasLink(Topic $topic, Reply $reply, Crawler $crawler): void
    {
        $link = $this->getReplyPermalink($crawler);
        $this->assertStringStartsWith(
            $this->useNumericPermalinksHTML ? $topic->getNumericPermalink() : $topic->getSamplePermalink(),
            $link->attr('href') ?? throw new \RuntimeException(),
        );
        $this->assertStringEndsWith(
            (string) $reply->getId(),
            $link->attr('href') ?? throw new \RuntimeException(),
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
