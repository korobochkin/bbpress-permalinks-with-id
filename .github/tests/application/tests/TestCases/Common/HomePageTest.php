<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\Common;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowserActions;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class HomePageTest extends AbstractForumsPageTest
{
    public function testForumsPageCreation(): void
    {
        $this->browsers->admin->followRedirects(true);

        BrowserActions::createPostViaWPAdmin($this->browsers->admin, $this->forumsPage);

        $this->assertPageStatusIs200($this->browsers->admin->getResponse());
    }

    #[Attributes\Depends('testForumsPageCreation')]
    public function testForumsPageAsGuest(): void
    {
        parent::testForumsPageAsGuest();
    }

    #[Attributes\Depends('testForumsPageAsGuest')]
    public function testForumsPageAsAdmin(): void
    {
        parent::testForumsPageAsAdmin();
    }
}
