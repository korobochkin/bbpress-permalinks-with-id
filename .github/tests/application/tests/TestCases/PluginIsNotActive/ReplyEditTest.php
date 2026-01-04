<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractReplyEditTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class ReplyEditTest extends AbstractReplyEditTest
{
    #[Attributes\DependsOnClass(ReplyTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getRepliesEdit')]
    public function testReplyEditAsGuest(Forum $forum, Topic $topic, Reply $reply): void
    {
        $this->_testReplyEditAsGuest($this->browsers->guest, $topic, $reply);
    }

    #[Attributes\Depends('testReplyEditAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getRepliesEdit')]
    public function testReplyEditAsAdmin(Forum $forum, Topic $topic, Reply $reply): void
    {
        $this->_testReplyEditAsAdmin($this->browsers->admin, $forum, $topic, $reply);
    }
}
