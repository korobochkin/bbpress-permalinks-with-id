<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\DataProviders\ForumDataProvider;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services\BrowsersService;
use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\ForumsPage;

$GLOBALS['BROWSERS_SERVICE'] = new BrowsersService();
ForumDataProvider::prepareInstance();
ForumsPage::prepareInstance();
