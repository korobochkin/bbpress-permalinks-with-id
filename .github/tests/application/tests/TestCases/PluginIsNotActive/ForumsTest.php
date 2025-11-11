<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumsTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class ForumsTest extends AbstractForumsTest
{
    //	#[Attributes\DependsOnClass(ForumsPage::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'get')]
    public function testForumsAsGuest(Post $forum): void
    {
        parent::testForumsAsGuest($forum);
    }
}
