<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\Common;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractHttpTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class HomePageTest extends AbstractHttpTestCase
{
    public function setUp(): void
    {
        var_dump($this->browsers->guest->getResponse()->getHeaders());
    }

    public function testSomething()
    {
        $this->assertTrue(true);
    }
}
