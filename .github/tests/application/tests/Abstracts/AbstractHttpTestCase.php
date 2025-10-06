<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowsersService;
use PHPUnit\Framework\TestCase;

abstract class AbstractHttpTestCase extends TestCase
{
    protected BrowsersService $browsers {
        get {
            if (isset($GLOBALS['BROWSERS_SERVICE'])
                && is_a($GLOBALS['BROWSERS_SERVICE'], BrowsersService::class)
            ) {
                return $GLOBALS['BROWSERS_SERVICE'];
            }

            throw new \Exception();
        }
    }
}
