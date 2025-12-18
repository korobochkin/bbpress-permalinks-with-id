<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumEditTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class ForumEditTest extends AbstractForumEditTest
{
    #[Attributes\DependsOnClass(ForumPagedTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getForums')]
    public function testForumEditAsGuest(Forum $forum): void
    {
        $this->_testForumEditAsGuest($this->browsers->guest, $forum);
    }

    #[Attributes\Depends('testForumEditAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getForumsForEdit')]
    public function testForumEditAsAdmin(Forum $forum, Forum $newForum): void
    {
        $this->_testForumEditAsAdmin($this->browsers->admin, $forum, $newForum);
    }
}
