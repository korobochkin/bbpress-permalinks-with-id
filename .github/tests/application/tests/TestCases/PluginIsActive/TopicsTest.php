<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractTopicsTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class TopicsTest extends AbstractTopicsTest
{
    #[Attributes\DependsOnClass(ForumsTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopics')]
    public function testTopicAsGuest(Forum $forum, Topic $topic): void
    {
        parent::testTopicAsGuest($forum, $topic);
    }

    #[Attributes\Depends('testTopicAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopics')]
    public function testTopicAsAdmin(Forum $forum, Topic $topic): void
    {
        parent::testTopicAsAdmin($forum, $topic);
    }

    #[Attributes\Depends('testTopicAsAdmin')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopics')]
    public function testTopicAsGuestNumeric(Forum $forum, Topic $topic): void
    {
        $this->useNumericPermalinks = true;
        parent::testTopicAsGuest($forum, $topic);
    }

    #[Attributes\Depends('testTopicAsGuestNumeric')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopics')]
    public function testTopicAsAdminNumeric(Forum $forum, Topic $topic): void
    {
        $this->useNumericPermalinks = true;
        parent::testTopicAsAdmin($forum, $topic);
    }
}
