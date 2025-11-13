<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\PluginIsNotActive;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractForumsTest;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowserActions;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\ForumsPage;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
class ForumsTest extends AbstractForumsTest
{
    #[Attributes\DependsOnClass(ForumsPage::class)]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'get')]
    public function testCreateForums(Post $forum): void
    {
        $this->browsers->admin->followRedirects(true);
        BrowserActions::createPostViaWPAdmin($this->browsers->admin, $forum);
        $this->assertPageStatusIs200($this->browsers->admin->getResponse());
    }

    #[Attributes\Depends('testCreateForums')]
    #[Attributes\DataProviderExternal(ForumDataProvider::class, 'get')]
    public function testForumsAsGuest(Post $forum): void
    {
        parent::testForumsAsGuest($forum);
    }
}
