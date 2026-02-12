<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Interfaces\BbPressPostInterface;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Traits\ParentForumIdTrait;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts\Traits\ParentTopicIdTrait;

final class Reply extends AbstractPost implements BbPressPostInterface
{
    use ParentForumIdTrait;
    use ParentTopicIdTrait;

    public function getType(): Type
    {
        return Type::Reply;
    }
}
