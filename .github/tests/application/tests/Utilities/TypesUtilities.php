<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

final class TypesUtilities
{
    /**
     * @return non-falsy-string
     *
     * @throws \RuntimeException
     */
    public static function getNonFalsyString(?string $value): string
    {
        $value = trim($value ?? throw new \RuntimeException());

        if ($value) {
            return $value;
        }

        throw new \RuntimeException();
    }

    /**
     * @return positive-int
     *
     * @throws \RuntimeException
     */
    public static function getPositiveInt(?string $value): int
    {
        $value = (int) trim($value ?? throw new \RuntimeException());

        if ($value > 0) {
            return $value;
        }

        throw new \RuntimeException();
    }
}
