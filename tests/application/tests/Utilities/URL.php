<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Forum;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\BbPressPostInterface;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\PostInterface;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Reply;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Topic;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Type;

final class URL
{
    /**
     * @var non-empty-array<string, string>
     */
    private static $edit = ['edit' => '1'];

    /**
     * @param non-falsy-string $home
     *
     * @return non-falsy-string
     */
    public static function getPlainNumericPermalink(string $home, PostInterface $post, array $extraQuery = []): string
    {
        $query = http_build_query([
            'post_type' => $post->getType()->value,
            'p' => $post->getId(),
            ...$extraQuery,
        ]);

        return $home.'/?'.$query;
    }

    /**
     * @param non-falsy-string $home
     *
     * @return non-falsy-string
     *
     * @throws \LogicException
     */
    public static function getPlainSlugPermalink(string $home, PostInterface $post, array $extraQuery = []): string
    {
        if (Type::Page === $post->getType()) {
            throw new \LogicException('This kind of permalinks is not available for the post type');
        }
        $query = http_build_query([
            $post->getType()->value => $post->getName(),
            ...$extraQuery,
        ]);

        return $home.'/?'.$query;
    }

    /**
     * @throws \LogicException
     */
    public static function pagePermalink(Forum|Topic $post, int $page, bool $useNumericPermalinks): string
    {
        return self::paged(
            $useNumericPermalinks ? $post->getNumericPermalink() : $post->getSamplePermalink(),
            $page,
        );
    }

    /**
     * @throws \LogicException
     */
    public static function editPermalink(BbPressPostInterface $post, bool $useNumericPermalinks): string
    {
        $permalink = $useNumericPermalinks ? $post->getNumericPermalink() : $post->getSamplePermalink();

        if (!str_ends_with($permalink, '/')) {
            throw new \LogicException('Invalid permalink format');
        }

        return $permalink.'edit/';
    }

    /**
     * @param non-falsy-string $home
     *
     * @return non-falsy-string
     */
    public static function editPlainPermalink(string $home, BbPressPostInterface $post, bool $useNumericPermalinks): string
    {
        return $useNumericPermalinks
            ? self::getPlainNumericPermalink($home, $post, self::$edit)
            : self::getPlainSlugPermalink($home, $post, self::$edit);
    }

    /**
     * @return non-falsy-string
     *
     * @throws \LogicException
     */
    public static function editPermalinkEditAsPlain(BbPressPostInterface $post, bool $useNumericPermalinks): string
    {
        $permalink = $useNumericPermalinks ? $post->getNumericPermalink() : $post->getSamplePermalink();

        if (!str_ends_with($permalink, '/')) {
            throw new \LogicException('Invalid permalink format');
        }

        $query = http_build_query(self::$edit);

        return $permalink.'?'.$query;
    }

    /**
     * @throws \LogicException
     */
    public static function replyAnchoredPermalink(Topic $topic, Reply $reply, bool $useNumericPermalinks): string
    {
        $permalink = $useNumericPermalinks ? $topic->getNumericPermalink() : $topic->getSamplePermalink();

        if (!str_ends_with($permalink, '/')) {
            throw new \LogicException('Invalid permalink format');
        }

        return $permalink.self::replyAnchor($reply);
    }

    /**
     * @return non-empty-string
     */
    public static function replyAnchor(Reply $reply): string
    {
        return '#post-'.$reply->getId();
    }

    /**
     * @throws \LogicException
     */
    private static function paged(string $permalink, int $page): string
    {
        if (!str_ends_with($permalink, '/')) {
            throw new \LogicException('Invalid permalink format');
        }

        return $permalink.'page/'.$page.'/';
    }
}
