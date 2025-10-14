<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\Common;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractHttpTestCase;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Status;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Type;
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
        $post = new Post();
        $post->setType(Type::Page);
        $post->setTitle('Custom title. '.random_int(0, PHP_INT_MAX));
        $post->setStatus(Status::Publish);
        $post->setName('custom-name-'.random_int(0, PHP_INT_MAX));

        BrowserActions::createPostViaWPAdmin($this->browsers->admin, $post);

        $this->assertEquals(200, $this->browsers->admin->getResponse()->getStatusCode());
    }
}
