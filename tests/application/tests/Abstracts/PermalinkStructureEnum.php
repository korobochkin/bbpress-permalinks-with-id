<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

enum PermalinkStructureEnum: string
{
    case PLAIN = 'plain';
    case SLUG = 'slug';
    case NUMERIC = 'numeric';
}
