<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services;

use Symfony\Component\BrowserKit\Request;

final class HttpBrowser extends \Symfony\Component\BrowserKit\HttpBrowser
{
    protected function getHeaders(Request $request): array
    {
        $headers = parent::getHeaders($request);

        // Disallows gzip in responses.
        $headers['accept-encoding'] = 'identity';

        return $headers;
    }
}
