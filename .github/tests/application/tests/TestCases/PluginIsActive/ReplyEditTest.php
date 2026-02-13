<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractReplyEditTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\PostUtilities;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
final class ReplyEditTest extends AbstractReplyEditTest
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->useNumericPermalinksHTML = true;
    }

    #[Attributes\DependsOnClass(ReplyNumericTest::class)]
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

    #[Attributes\Depends('testReplyEditAsAdmin')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getRepliesEdit')]
    public function testReplySubmitEditAsAdmin(Forum $forum, Topic $topic, Reply $reply): void
    {
        $this->_testReplySubmitEditAsAdmin($this->browsers->admin, $forum, $topic, $reply, PostUtilities::copyAndEditTitleAndContent($reply));
    }
}
