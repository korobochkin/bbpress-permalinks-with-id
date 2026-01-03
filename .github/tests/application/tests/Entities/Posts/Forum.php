<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\BbPressPostInterface;

class Forum extends AbstractPost implements BbPressPostInterface
{
    public function getType(): Type
    {
        return Type::Forum;
    }
}
