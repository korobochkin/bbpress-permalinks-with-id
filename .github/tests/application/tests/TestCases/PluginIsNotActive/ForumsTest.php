<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumsTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowserActions;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class ForumsTest extends AbstractForumsTest
{
    #[Attributes\DependsOnClass(ForumsPageTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'get')]
    public function testCreateForum(Post $forum): void
    {
        $this->browsers->admin->followRedirects(true);
        BrowserActions::createPostViaWPAdmin($this->browsers->admin, $forum);
        $this->assertPageStatusIs200($this->browsers->admin->getResponse());
    }

    #[Attributes\Depends('testCreateForum')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'get')]
    public function testForumAsGuest(Post $forum): void
    {
        parent::testForumAsGuest($forum);
    }

    #[Attributes\Depends('testForumAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'get')]
    public function testForumAsAdmin(Post $forum): void
    {
        parent::testForumAsAdmin($forum);
    }
}
