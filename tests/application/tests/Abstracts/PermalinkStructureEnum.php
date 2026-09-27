<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

enum PermalinkStructureEnum: string
{
    case PLAIN_NUMERIC = 'plain-numeric';
    case PLAIN_SLUG = 'plain-slug';
    case SLUG = 'slug';
    case NUMERIC = 'numeric';
}
