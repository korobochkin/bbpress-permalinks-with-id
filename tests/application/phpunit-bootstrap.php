<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumsPageDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowsersService;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\WordPressServerLogs;

$GLOBALS['BROWSERS_SERVICE'] = new BrowsersService();
$GLOBALS['WORDPRESS_LOGS_SERVICE'] = new WordPressServerLogs();
ForumDataProvider::prepareInstance();
ForumsPageDataProvider::prepareInstance();
