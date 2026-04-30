<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumPagedTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
final class ForumPagedTest extends AbstractForumPagedTest
{
    /**
     * @param int<1, max> $page
     * @param list<Topic> $topics
     *
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    #[Attributes\DependsOnClass(TopicTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopicsPaged')]
    public function testForumPagedAsGuest(Forum $forum, int $page, array $topics): void
    {
        $this->_testForumPaged($this->browsers->guest, $forum, $page, $topics);
    }

    /**
     * @param int<1, max> $page
     * @param list<Topic> $topics
     *
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    #[Attributes\Depends('testForumPagedAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopicsPaged')]
    public function testForumPagedAsAdmin(Forum $forum, int $page, array $topics): void
    {
        $this->_testForumPaged($this->browsers->admin, $forum, $page, $topics);
    }
}
