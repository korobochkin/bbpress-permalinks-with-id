<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
final class ForumNumericTest extends AbstractForumTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useNumericPermalinksRequests = true;
        $this->useNumericPermalinksHTML = true;
        $this->forumsAreEmpty = false;
    }

    #[Attributes\DependsOnClass(ForumPagedTest::class)]
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
