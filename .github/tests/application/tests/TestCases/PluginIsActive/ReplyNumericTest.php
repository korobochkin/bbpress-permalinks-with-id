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
final class ReplyNumericTest extends AbstractReplyTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useNumericPermalinksRequests = true;
        $this->useNumericPermalinksHTML = true;
    }

    #[Attributes\DependsOnClass(ReplyTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getReplies')]
    public function testReplyAsGuest(Forum $forum, Topic $topic, Reply $reply): void
    {
        parent::testReplyAsGuest($forum, $topic, $reply);
    }

    #[Attributes\Depends('testReplyAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getReplies')]
    public function testReplyAsAdmin(Forum $forum, Topic $topic, Reply $reply): void
    {
        parent::testReplyAsAdmin($forum, $topic, $reply);
    }
}
