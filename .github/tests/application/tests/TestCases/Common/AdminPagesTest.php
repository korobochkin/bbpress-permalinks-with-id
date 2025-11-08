<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\Common;

use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class AdminPagesTest extends AbstractAdminPagesTest
{
    public function testIndexPage(): void
    {
        parent::testIndexPage();
    }
}
