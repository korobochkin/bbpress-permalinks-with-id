<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractTopicTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
final class TopicTest extends AbstractTopicTest
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->useNumericPermalinksHTML = true;
    }

    #[\Override]
    #[Attributes\DependsOnClass(ForumNumericEditTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopics')]
    public function testTopicAsGuest(Forum $forum, Topic $topic): void
    {
        parent::testTopicAsGuest($forum, $topic);
    }

    #[\Override]
    #[Attributes\Depends('testTopicAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopics')]
    public function testTopicAsAdmin(Forum $forum, Topic $topic): void
    {
        parent::testTopicAsAdmin($forum, $topic);
    }
}
