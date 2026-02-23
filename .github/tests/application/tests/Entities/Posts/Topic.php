<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\BbPressPostInterface;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Traits\ParentForumIdTrait;

/**
 * @psalm-suppress MissingConstructor
 */
final class Topic extends AbstractPost implements BbPressPostInterface
{
    use ParentForumIdTrait;

    #[\Override]
    public function getType(): Type
    {
        return Type::Topic;
    }
}
