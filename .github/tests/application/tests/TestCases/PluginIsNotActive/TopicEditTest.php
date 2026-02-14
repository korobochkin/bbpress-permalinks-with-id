<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractTopicEditTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
final class TopicEditTest extends AbstractTopicEditTest
{
    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    #[Attributes\DependsOnClass(TopicPagedTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopics')]
    public function testTopicEditAsGuest(Forum $forum, Topic $topic): void
    {
        $this->_testTopicEditAsGuest($this->browsers->guest, $forum, $topic);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    #[Attributes\Depends('testTopicEditAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getTopics')]
    public function testTopicEditAsAdmin(Forum $forum, Topic $topic): void
    {
        $this->_testTopicEditAsAdmin($this->browsers->admin, $forum, $topic);
    }
}
