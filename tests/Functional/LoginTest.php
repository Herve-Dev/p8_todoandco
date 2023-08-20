<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testIsLoginSuccess(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('login'));

        //Form
        $form = $crawler->filter("form[name=login]")->form([
            "username" => "adminTodo",
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
            "username" => "adminTodo",
            "password" => "Password"
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertRouteSame('login');
        $this->assertSelectorTextContains("div.alert-danger", "Invalid credentials");

    }

    public function testLogoutUser(): void 
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get("router");
        $client->request('GET', $urlGenerator->generate('homepage'));
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);
        $client->loginUser($user);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertRouteSame('login');

    }
}
