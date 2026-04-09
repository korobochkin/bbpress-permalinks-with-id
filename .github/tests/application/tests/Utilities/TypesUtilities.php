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
}
