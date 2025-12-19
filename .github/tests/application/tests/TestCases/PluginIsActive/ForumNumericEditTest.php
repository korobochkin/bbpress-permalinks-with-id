<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumEditTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class ForumNumericEditTest extends AbstractForumEditTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useNumericPermalinksRequests = true;
        $this->useNumericPermalinksHTML = true;
    }

    #[Attributes\DependsOnClass(ForumEditTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getForums')]
    public function testForumEditAsGuest(Forum $forum): void
    {
        $this->_testForumEditAsGuest($this->browsers->guest, $forum);
    }

    #[Attributes\Depends('testForumEditAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getForums')]
    public function testForumEditAsAdmin(Forum $forum): void
    {
        $this->_testForumEditAsAdmin($this->browsers->admin, $forum);
    }

    #[Attributes\Depends('testForumEditAsAdmin')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getForumsForEdit')]
    public function testForumSubmitEditAsAdmin(Forum $forum, Forum $newForum): void
    {
        $this->_testForumSubmitEditAsAdmin($this->browsers->admin, $forum, $newForum);
    }
}
