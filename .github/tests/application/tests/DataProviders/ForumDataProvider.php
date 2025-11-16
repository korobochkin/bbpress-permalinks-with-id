<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Post;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Type;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Random;

class ForumDataProvider
{
    public static array $data;

    public static function get(): array
    {
        if (!isset(self::$data)) {
            self::build();
        }

        return self::$data;
    }

    private static function build(): void
    {
        for ($i = 0; $i < 2; ++$i) {
            self::$data[$i] = [self::buildPost($i)];
        }
    }

    private static function buildPost(int $iteration): Post
    {
        $post = new Post();
        $random = Random::positiveInteger();
        $post
            ->setType(Type::Forum)
            ->setTitle('Forum # '.$iteration.'. '.$random)
            ->setContent(Random::sentence())
            ->setName('forum-slug-'.$iteration.'-'.$random.'-end')
        ;

        return $post;
    }
}
