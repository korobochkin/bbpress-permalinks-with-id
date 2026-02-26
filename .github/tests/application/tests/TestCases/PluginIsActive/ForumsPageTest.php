<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumsPageTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumsPageDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Page;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive\ActivatePluginTest;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
final class ForumsPageTest extends AbstractForumsPageTest
{
    #[\Override]
    #[Attributes\DependsOnClass(ActivatePluginTest::class)]
    #[Attributes\DataProviderExternal(ForumsPageDataProvider::class, 'get')]
    public function testForumsPageAsGuest(Page $forumsPage): void
    {
        parent::testForumsPageAsGuest($forumsPage);
    }

    #[\Override]
    #[Attributes\DependsOnClass(ActivatePluginTest::class)]
    #[Attributes\DataProviderExternal(ForumsPageDataProvider::class, 'get')]
    public function testForumsPageAsAdmin(Page $forumsPage): void
    {
        parent::testForumsPageAsAdmin($forumsPage);
    }
}
