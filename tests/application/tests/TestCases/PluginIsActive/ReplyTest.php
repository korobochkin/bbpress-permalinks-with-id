<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractReplyTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
final class ReplyTest extends AbstractReplyTest
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->useNumericPermalinksHTML = true;
    }

    #[\Override]
    #[Attributes\DependsOnClass(TopicNumericEditTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getReplies')]
    public function testReplyAsGuest(Forum $forum, Topic $topic, Reply $reply): void
    {
        parent::testReplyAsGuest($forum, $topic, $reply);
    }

    #[\Override]
    #[Attributes\Depends('testReplyAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getReplies')]
    public function testReplyAsAdmin(Forum $forum, Topic $topic, Reply $reply): void
    {
        parent::testReplyAsAdmin($forum, $topic, $reply);
    }
}
