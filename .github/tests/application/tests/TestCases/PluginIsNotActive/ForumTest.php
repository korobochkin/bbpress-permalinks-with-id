<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Status;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowserActions;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
final class ForumTest extends AbstractForumTest
{
    #[Attributes\DependsOnClass(ForumsPageTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getForums')]
    public function testCreateForum(Forum $forum): void
    {
        $this->browsers->admin->followRedirects(true);
        BrowserActions::createPostViaWPAdmin($this->browsers->admin, $forum);
        $this->assertPageStatusIs200($this->browsers->admin->getResponse());

        $this->assertIsInt($forum->getId());
        $this->assertSame(Status::Publish, $forum->getStatus());
        $this->assertSampleLinkIsOk($forum);
    }

    #[\Override]
    #[Attributes\Depends('testCreateForum')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getForums')]
    public function testForumAsGuest(Forum $forum): void
    {
        parent::testForumAsGuest($forum);
    }

    #[\Override]
    #[Attributes\Depends('testForumAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getForums')]
    public function testForumAsAdmin(Forum $forum): void
    {
        parent::testForumAsAdmin($forum);
    }
}
