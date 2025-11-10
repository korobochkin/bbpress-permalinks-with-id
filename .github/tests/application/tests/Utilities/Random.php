<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

class Random
{
    public static function positiveInteger(): int
    {
        return random_int(1, PHP_INT_MAX);
    }
}
