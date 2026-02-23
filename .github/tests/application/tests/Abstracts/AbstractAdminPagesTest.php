<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Abstracts;

use Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Utilities\HttpUtilities;

abstract class AbstractAdminPagesTest extends AbstractHttpTestCase
{
    /**
     * @throws \Symfony\Component\BrowserKit\Exception\LogicException
     */
    public function testIndexPage(): void
    {
        $requestedUri = '/wp-admin/index.php';

        $this->browsers->admin->followRedirects(false);
        $this->browsers->admin->request('GET', $requestedUri);
        $response = $this->browsers->admin->getResponse();

        $this->assertPageStatusIs200OrRedirect($response);

        // In some cases (different versions of plugins and WordPress core) it could redirect user
        // on first admin area access to a page like /wp-admin/index.php?page=bbp-about
        // That's why I first follow redirect and then access /wp-admin/index.php one more time.
        if (HttpUtilities::isRedirect($response)) {
            $this->browsers->admin->followRedirect();

            $this->assertPageStatusIs200($this->browsers->admin->getResponse());

            $this->browsers->admin->request('GET', $requestedUri);

            $this->assertPageStatusIs200($this->browsers->admin->getResponse());
        }
    }
}
