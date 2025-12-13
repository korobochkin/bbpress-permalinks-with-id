<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumPagedTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class ForumPagedTest extends AbstractForumPagedTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useNumericPermalinksHTML = true;
    }

    /**
     * @param Topic[] $topics
     */
    #[Attributes\DependsOnClass(ForumTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopicsPaged')]
    public function testForumPagedAsGuest(Forum $forum, int $page, array $topics): void
    {
        $this->_testForumPaged($this->browsers->guest, $forum, $page, $topics);
    }

    /**
     * @param Topic[] $topics
     */
    #[Attributes\Depends('testForumPagedAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopicsPaged')]
    public function testForumPagedAsAdmin(Forum $forum, int $page, array $topics): void
    {
        $this->_testForumPaged($this->browsers->admin, $forum, $page, $topics);
    }
}
