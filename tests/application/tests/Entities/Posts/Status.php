<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Entities\Posts;

enum Status: string
{
    case AutoDraft = 'auto-draft';

    case Draft = 'draft';

    case Publish = 'publish';

    case Trash = 'trash';
}
