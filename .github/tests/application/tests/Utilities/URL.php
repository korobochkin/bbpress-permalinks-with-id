<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;

class URL
{
    public static function pagePermalink(Forum|Topic $post, int $page, bool $useNumericPermalinks): string
    {
        return self::paged(
            $useNumericPermalinks ? $post->getNumericPermalink() : $post->getSamplePermalink(),
            $page,
        );
    }

    public static function editPermalink(Forum|Reply|Topic $post, bool $useNumericPermalinks): string
    {
        $permalink = $useNumericPermalinks ? $post->getNumericPermalink() : $post->getSamplePermalink();

        if (!str_ends_with($permalink, '/')) {
            throw new \LogicException('Invalid permalink format');
        }

        return $permalink.'edit/';
    }

    private static function paged(string $permalink, int $page): string
    {
        if (!str_ends_with($permalink, '/')) {
            throw new \LogicException('Invalid permalink format');
        }

        return $permalink.'page/'.$page.'/';
    }
}
