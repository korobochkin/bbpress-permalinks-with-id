<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Page;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Status;

final class ForumsPage
{
    private static Page $forums;

    public static function generate(): Page
    {
        $post = new Page();
        $post
            ->setTitle('Forums. '.Random::positiveInteger())
            ->setStatus(Status::Publish)
            ->setName('forums')
        ;

        return $post;
    }

    public static function get(): Page
    {
        if (!isset(static::$forums)) {
            static::$forums = self::generate();
        }

        return static::$forums;
    }
}
