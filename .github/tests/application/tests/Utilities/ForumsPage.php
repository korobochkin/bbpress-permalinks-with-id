<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Status;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Type;

class ForumsPage
{
    private static Post $forums;

    public static function generate(): Post
    {
        $post = new Post();
        $post
            ->setType(Type::Page)
            ->setTitle('Forums. '.Random::positiveInteger())
            ->setStatus(Status::Publish)
            ->setName('forums')
        ;

        return $post;
    }

    public static function get(): Post
    {
        if (!isset(static::$forums)) {
            static::$forums = self::generate();
        }

        return static::$forums;
    }
}
