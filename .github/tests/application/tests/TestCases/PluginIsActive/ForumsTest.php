<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumsTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class ForumsTest extends AbstractForumsTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useNumericPermalinksHTML = true;
        $this->forumsAreEmpty = false;
    }

    #[Attributes\DependsOnClass(ForumsPageTest::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getForums')]
    public function testForumAsGuest(Forum $forum): void
    {
        parent::testForumAsGuest($forum);
    }

    #[Attributes\Depends('testForumAsGuest')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'getForums')]
    public function testForumAsAdmin(Forum $forum): void
    {
        parent::testForumAsAdmin($forum);
    }
}
