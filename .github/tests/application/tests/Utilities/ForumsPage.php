<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Page;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Status;

final class ForumsPage
{
    private static Page $forums;

    /**
     * @throws \Random\RandomException
     */
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

    /**
     * @throws \Random\RandomException
     */
    public static function get(): Page
    {
        if (!isset(self::$forums)) {
            self::$forums = self::generate();
        }

        return self::$forums;
    }
}
