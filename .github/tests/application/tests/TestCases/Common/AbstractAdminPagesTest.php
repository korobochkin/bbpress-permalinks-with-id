<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\TestCases\Common;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts\AbstractHttpTestCase;
use PHPUnit\Framework\Attributes;

/**
 * @internal
 */
#[Attributes\CoversNothing]
abstract class AbstractAdminPagesTest extends AbstractHttpTestCase
{
	public function testIndexPage(): void
	{
		$this->browsers->admin->followRedirects(true);
		$crawler = $this->browsers->admin->request('GET', '/wp-admin/index.php');
		$this->assertPageStatusIs200($this->browsers->admin->getResponse());
	}
}