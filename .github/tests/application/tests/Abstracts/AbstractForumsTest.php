<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use Symfony\Component\BrowserKit\HttpBrowser;

abstract class AbstractForumsTest extends AbstractHttpTestCase
{
    public function testForumsAsGuest(Post $forum): void
    {
        $this->browsers->guest->followRedirects(false);
    }

    public function testForumsAsAdmin(): void
    {
        $this->browsers->admin->followRedirects(false);
    }

    protected function testForums(HttpBrowser $browser): void
    {
        $crawler = $browser->request('GET', '/');
    }
}
