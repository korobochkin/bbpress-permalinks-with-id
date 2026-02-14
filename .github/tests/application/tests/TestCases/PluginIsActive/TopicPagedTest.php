<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractTopicPagedTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
final class TopicPagedTest extends AbstractTopicPagedTest
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->useNumericPermalinksHTML = true;
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    #[Attributes\DependsOnClass(TopicTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getRepliesPaged')]
    public function testTopicPagedAsGuest(Forum $forum, Topic $topic, int $page, array $replies): void
    {
        $this->_testTopicPaged($this->browsers->guest, $forum, $topic, $page, $replies);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    #[Attributes\Depends('testTopicPagedAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getRepliesPaged')]
    public function testTopicPagedAsAdmin(Forum $forum, Topic $topic, int $page, array $replies): void
    {
        $this->_testTopicPaged($this->browsers->admin, $forum, $topic, $page, $replies);
    }
}
