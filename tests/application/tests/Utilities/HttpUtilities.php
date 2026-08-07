<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities;

use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\BrowserKit\Response;

final class HttpUtilities
{
    /**
     * @see AbstractBrowser::request
     */
    public static function isRedirect(Response $response): bool
    {
        $status = $response->getStatusCode();

        return $status >= 300 && $status < 400;
    }
}
