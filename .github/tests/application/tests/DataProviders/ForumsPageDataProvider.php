<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Page;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Status;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\Random;

final class ForumsPageDataProvider
{
    private static self $instance;

    private Page $forums;

    /**
     * @throws \Random\RandomException
     */
    public function __construct()
    {
        $this->prepare();
    }

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
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @return array{array{Page}}
     */
    public static function get(): array
    {
        return [[self::$instance->forums]];
    }

    /**
     * @throws \Random\RandomException
     */
    public static function prepareInstance(): void
    {
        self::$instance = new self();
    }

    /**
     * @throws \Random\RandomException
     */
    private function prepare(): void
    {
        $this->forums = self::generate();
    }
}
