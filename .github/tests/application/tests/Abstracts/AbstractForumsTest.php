<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use Symfony\Component\BrowserKit\HttpBrowser;

abstract class AbstractForumsTest extends AbstractHttpTestCase
{
    public function testForumsAsGuest(Post $forum): void
    {
        $this->testForum($this->browsers->guest, $forum);
    }

    public function testForumsAsAdmin(Post $forum): void
    {
        $this->testForum($this->browsers->admin, $forum);
    }

    protected function testForum(HttpBrowser $browser, Post $forum): void
    {
        $browser->followRedirects(false);
        $crawler = $browser->request('GET', '/forums/forum/'.$forum->getId().'/');

        $this->assertPageStatusIs200($browser->getResponse());
        $this->assertPageTitleEquals($forum->getTitle(), $crawler);
        $this->assertBbPressBreadCrumbsContains($forum->getTitle(), $crawler);
    }
}
