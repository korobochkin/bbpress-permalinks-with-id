<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services;

enum TestSiteCredentials: string
{
    case HOME = 'WORDPRESS_SITE_HOME';
    case ADMIN_LOGIN = 'WORDPRESS_ADMIN_LOGIN';
    case ADMIN_PASSWORD = 'WORDPRESS_ADMIN_PASSWORD';
}
