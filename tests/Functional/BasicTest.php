<?php

namespace App\Tests\App\Test\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BasicTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        // Assurez-vous que la réponse est une redirection (statut 3xx)
        $this->assertTrue($client->getResponse()->isRedirect());

        // Assurez-vous que la redirection pointe vers /login
        $this->assertEquals('/login', $client->getResponse()->headers->get('Location'));
    }
}
