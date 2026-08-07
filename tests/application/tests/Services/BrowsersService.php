<?php

declare(strict_types=1);

namespace Korobochkin\BBPressPermalinksWithIdTestsApplication\Tests\Services;

use Symfony\Component\HttpClient\HttpClient;

final class BrowsersService
{
    public HttpBrowser $admin;

    public HttpBrowser $guest;

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $this->setUp();
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
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

    /**
     * @throws \InvalidArgumentException
     */
    private function getHomePageURL(): string
    {
        return $this->getEnvOrThrowError(TestSiteCredentials::HOME);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function getEnvOrThrowError(TestSiteCredentials $name): string
    {
        $value = getenv($name->value);
        if (
            \is_string($value)
            && '' !== $value
        ) {
            return $value;
        }

        throw new \InvalidArgumentException("Required ENV variable is not defined: {$name->value}");
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    private function logIn(HttpBrowser $browser, string $login, string $password): void
    {
        $crawler = $browser->request('GET', '/wp-login.php');

        $form = $crawler->selectButton('Log In')->form();

        $browser->submit($form, [
            'log' => $login,
            'pwd' => $password,
        ]);

        $response = $browser->getResponse();

        if (200 !== $response->getStatusCode() || str_contains($response->getContent(), 'login_error')) {
            throw new \RuntimeException('Failed to login');
        }
    }
}
