<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class BrowsersService
{
    public HttpBrowser $admin;

    public HttpBrowser $guest;

    public function __construct()
    {
        $this->setUp();
    }

    private function setUp(): void
    {
        $this->admin = new HttpBrowser(HttpClient::create([
            'timeout' => 30,
            'max_redirects' => 10,
        ]));

        $this->guest = new HttpBrowser(HttpClient::create([
            'timeout' => 30,
            'max_redirects' => 10,
        ]));

        $home = $this->getHomePageURL();

        $this->admin->request('GET', $home);

        $this->logIn(
            $this->admin,
            $this->getEnvOrThrowError(TestSiteCredentials::ADMIN_LOGIN),
            $this->getEnvOrThrowError(TestSiteCredentials::ADMIN_PASSWORD)
        );

        $this->guest->request('GET', $home);
    }

    public function createPostViaWPAdmin(HttpBrowser $browser): object
    {
        $crawler = $browser->request('GET', '/wp-admin/post-new.php?post_type=forum');

        $nonce = $crawler->filterXPath('//form//input[(@id="_wpnonce" or name="_wp_nonce") and @type="hidden"]');
        $postID = $crawler->filterXPath('//form//input[(@id="post_ID" or name="post_ID") and @type="hidden"]');
        $postType = $crawler->filterXPath('//form//input[(@id="post_type" or name="post_type") and @type="hidden"]');
        $action = $crawler->filterXPath('//form//input[(@id="hiddenaction" or name="action") and @type="hidden"]');
        $originalAction = $crawler->filterXPath('//form//input[(@id="originalaction" or name="originalaction") and @type="hidden"]');
        $userID = $crawler->filterXPath('//form//input[(@id="user-id" or name="user_ID") and @type="hidden"]');
        $originalPostStatus = $crawler->filterXPath('//form//input[(@id="original_post_status" or name="original_post_status") and @type="hidden"]');
        $referredBy = $crawler->filterXPath('//form//input[(@id="referredby" or name="referredby") and @type="hidden"]');

        // XPath for a form element with all required fields: //form[input[(@id="_wpnonce" or @name="_wp_nonce") and @type="hidden"]]

        $browser->request(
            'POST',
            '/wp-admin/post.php',
            [
                '_wpnonce' => $nonce->attr('value'),
                '_wp_http_referer' => '/wp-admin/post-new.php?post_type=post',
                'action' => $action->attr('value'),
                'originalaction' => $originalAction->attr('value'),
                'post_author' => $userID->attr('value'),
                'post_type' => $postType->attr('value'),
                'original_post_status' => $originalPostStatus->attr('value'),
                'referredby' => $referredBy->attr('value'),
                'post_ID' => $postID->attr('value'),
                'post_title' => 'My custom title 333',
                'content' => 'My custom content 333',
                'post_status' => 'publish', // 'draft'
                'post_name' => 'my-custom-slug-'.random_int(1000, PHP_INT_MAX), // slug
            ],
        );

        return $browser->getResponse();
    }

    private function getHomePageURL(): string
    {
        return $this->getEnvOrThrowError(TestSiteCredentials::HOME);
    }

    private function getEnvOrThrowError(TestSiteCredentials $name): string
    {
        return $_ENV[$name->value]
        ?? throw new \InvalidArgumentException(
            "Required ENV variable is not defined: {$name->value}"
        );
    }

    private function logIn(HttpBrowser $browser, string $login, string $password): void
    {
        $crawler = $browser->request('GET', '/wp-login.php');

        $form = $crawler->selectButton('Log In')->form();

        $browser->submit($form, [
            'log' => $login,
            'pwd' => $password,
        ]);

        $response = $browser->getResponse();

        if (200 !== $response->getStatusCode() || false !== strpos($response->getContent(), 'login_error')) {
            throw new \RuntimeException('Failed to login');
        }
    }
}
