<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractAdminPagesTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive\ActivatePluginTest;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class AdminPagesTest extends AbstractAdminPagesTest
{
    #[Attributes\DependsOnClass(ActivatePluginTest::class)]
    public function testIndexPage(): void
    {
        parent::testIndexPage();
    }
}
