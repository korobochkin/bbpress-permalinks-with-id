<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services;

enum TestSiteCredentials: string
{
    case HOME = 'TEST_SITE_HOME';
    case ADMIN_LOGIN = 'TEST_SITE_ADMIN_LOGIN';
    case ADMIN_PASSWORD = 'TEST_SITE_ADMIN_PASSWORD';
}
