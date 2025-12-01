<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Traits\ParentForumIdTrait;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Traits\ParentTopicIdTrait;

class Reply extends AbstractPost
{
    use ParentForumIdTrait;
    use ParentTopicIdTrait;

    public function getType(): Type
    {
        return Type::Reply;
    }
}
