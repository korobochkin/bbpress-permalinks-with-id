<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts;

class Forum extends AbstractPost
{
    public function getType(): Type
    {
        return Type::Forum;
    }
}
