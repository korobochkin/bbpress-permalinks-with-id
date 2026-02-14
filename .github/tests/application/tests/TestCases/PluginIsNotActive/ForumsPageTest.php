<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumsPageTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowserActions;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
final class ForumsPageTest extends AbstractForumsPageTest
{
    /**
     * @throws \InvalidArgumentException
     */
    #[Attributes\DependsOnClass(AdminPagesTest::class)]
    public function testForumsPageCreation(): void
    {
        $this->browsers->admin->followRedirects(true);

        BrowserActions::createPostViaWPAdmin($this->browsers->admin, $this->forumsPage);

        $this->assertPageStatusIs200($this->browsers->admin->getResponse());
    }

    #[\Override]
    #[Attributes\Depends('testForumsPageCreation')]
    public function testForumsPageAsGuest(): void
    {
        parent::testForumsPageAsGuest();
        $this->assertForumsPageHasNoForums($this->browsers->guest);
    }

    #[\Override]
    #[Attributes\Depends('testForumsPageAsGuest')]
    public function testForumsPageAsAdmin(): void
    {
        parent::testForumsPageAsAdmin();
        $this->assertForumsPageHasNoForums($this->browsers->admin);
    }
}
