<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumsPageTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumsPageDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Page;
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
     * @throws \RuntimeException
     */
    #[Attributes\DependsOnClass(AdminPagesTest::class)]
    #[Attributes\DataProviderExternal(ForumsPageDataProvider::class, 'get')]
    public function testForumsPageCreation(Page $forumsPage): void
    {
        $this->browsers->admin->followRedirects(true);

        BrowserActions::createPostViaWPAdmin($this->browsers->admin, $forumsPage);

        $this->assertPageStatusIs200($this->browsers->admin->getResponse());
    }

    #[\Override]
    #[Attributes\Depends('testForumsPageCreation')]
    #[Attributes\DataProviderExternal(ForumsPageDataProvider::class, 'get')]
    public function testForumsPageAsGuest(Page $forumsPage): void
    {
        parent::testForumsPageAsGuest($forumsPage);
        $this->assertForumsPageHasNoForums($this->browsers->guest);
    }

    #[\Override]
    #[Attributes\Depends('testForumsPageAsGuest')]
    #[Attributes\DataProviderExternal(ForumsPageDataProvider::class, 'get')]
    public function testForumsPageAsAdmin(Page $forumsPage): void
    {
        parent::testForumsPageAsAdmin($forumsPage);
        $this->assertForumsPageHasNoForums($this->browsers->admin);
    }
}
