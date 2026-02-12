<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumsPageTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive\ActivatePluginTest;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
final class ForumsPageTest extends AbstractForumsPageTest
{
    #[Attributes\DependsOnClass(ActivatePluginTest::class)]
    public function testForumsPageAsGuest(): void
    {
        parent::testForumsPageAsGuest();
    }

    #[Attributes\DependsOnClass(ActivatePluginTest::class)]
    public function testForumsPageAsAdmin(): void
    {
        parent::testForumsPageAsAdmin();
    }
}
