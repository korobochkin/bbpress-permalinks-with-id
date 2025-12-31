<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\BbPressPostInterface;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Traits\ParentForumIdTrait;

class Topic extends AbstractPost implements BbPressPostInterface
{
    use ParentForumIdTrait;

    public function getType(): Type
    {
        return Type::Topic;
    }
}
