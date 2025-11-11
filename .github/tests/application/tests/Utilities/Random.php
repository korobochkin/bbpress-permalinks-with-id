<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

class Random
{
    public static function positiveInteger(): int
    {
        return random_int(1, PHP_INT_MAX);
    }

    public static function word(): string
    {
        return bin2hex(random_bytes(random_int(1, 10)));
    }

    public static function sentence(): string
    {
        $length = random_int(0, 12);
        $sentence = [];

        for ($i = 0; $i < $length; ++$i) {
            $sentence[] = self::word();
        }

        return implode(' ', $sentence).'.';
    }
}
