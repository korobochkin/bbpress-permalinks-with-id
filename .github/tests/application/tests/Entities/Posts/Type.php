<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts;

enum Type: string
{
    case Post = 'post';

    case Page = 'page';

    case Forum = 'forum';

    case Topic = 'topic';

    case Reply = 'reply';
}
