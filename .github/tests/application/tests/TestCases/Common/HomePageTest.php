<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\Common;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractHttpTestCase;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowserActions;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class HomePageTest extends AbstractHttpTestCase
{
    public function setUp(): void
    {
        //        var_dump($this->browsers->guest->getResponse()->getHeaders());
    }

    public function testSomething()
    {
        $this->assertTrue(true);
    }

    public function testForumsPageCreation(): void
    {
        BrowserActions::createPostViaWPAdmin($this->browsers->admin);

        $this->assertEquals(200, $this->browsers->admin->getResponse()->getStatusCode());
    }
}
