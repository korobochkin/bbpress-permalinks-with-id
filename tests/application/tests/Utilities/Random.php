<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

final class Random
{
    /**
     * @return int<1, max>
     *
     * @throws \Random\RandomException
     */
    public static function positiveInteger(): int
    {
        return random_int(1, \PHP_INT_MAX);
    }

    /**
     * @param int<1, max> $maxLetters
     * @param int<1, max> $minLetters
     *
     * @return non-empty-string
     *
     * @throws \Random\RandomException
     * @throws \RuntimeException
     */
    public static function word(int $maxLetters = 10, int $minLetters = 1): string
    {
        $length = (int) ceil(random_int($minLetters, $maxLetters) / 2);

        if ($length >= 1) {
            return bin2hex(random_bytes($length));
        }

        throw new \RuntimeException();
    }

    /**
     * @param int<1, max> $maxWords
     * @param int<1, max> $minWords
     *
     * @return non-empty-string
     *
     * @throws \Random\RandomException
     */
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
