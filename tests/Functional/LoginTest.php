<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testIsLoginSuccess(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('login'));

        //Form
        $form = $crawler->filter("form[name=login]")->form([
            "username" => "ychauvin",
            "password" => "Password123"
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertRouteSame('homepage');

    }

    public function testIsLoginInvalid(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('login'));

        //Form
        $form = $crawler->filter("form[name=login]")->form([
            "username" => "ychauvin",
            "password" => "Password"
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertRouteSame('login');

        $this->assertSelectorTextContains("div.alert-danger", "Invalid credentials");

    }
}
