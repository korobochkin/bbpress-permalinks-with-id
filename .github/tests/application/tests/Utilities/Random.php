<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

class Random
{
    public static function positiveInteger(): int
    {
        return random_int(1, PHP_INT_MAX);
    }

    public static function word(int $maxLetters = 10, int $minLetters = 1): string
    {
        $length = random_int($minLetters, $maxLetters);

        return bin2hex(random_bytes((int) ceil($length / 2)));
    }

    public static function sentence(int $maxWords = 12, int $minWords = 1): string
    {
        $length = random_int($minWords, $maxWords);
        $sentence = [];

        for ($i = 0; $i < $length; ++$i) {
            $sentence[] = self::word();
        }

        return implode(' ', $sentence).'.';
    }
}
