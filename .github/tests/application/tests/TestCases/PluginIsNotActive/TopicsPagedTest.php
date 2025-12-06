<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractTopicsPagedTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class TopicsPagedTest extends AbstractTopicsPagedTest
{
    #[Attributes\DependsOnClass(RepliesTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getRepliesPaged')]
    public function testTopicPagedAsGuest(Forum $forum, Topic $topic, int $page, array $replies): void
    {
        $this->_testTopicPaged($this->browsers->guest, $forum, $topic, $page, $replies);
    }

    #[Attributes\Depends('testTopicPagedAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getRepliesPaged')]
    public function testTopicPagedAsAdmin(Forum $forum, Topic $topic, int $page, array $replies): void
    {
        $this->_testTopicPaged($this->browsers->admin, $forum, $topic, $page, $replies);
    }
}
