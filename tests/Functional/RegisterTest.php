<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Faker\Factory;

class RegisterTest extends WebTestCase
{
    public function testRegisterIsSuccess(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('user_create'));

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');

        $faker = Factory::create();


        //Récupérer le formulaire 
        $submitButton = $crawler->selectButton('Register');
        $form = $submitButton->form();

        $form['registration_form[roles]']->setValue('ROLE_USER');
        $form['registration_form[username]'] = $faker->userName();
        $form['registration_form[plainPassword][first]'] = "Password123";
        $form['registration_form[plainPassword][second]'] = "Password123";
        $form['registration_form[email]'] = $faker->email();
        
        //Soumettre le formulaire 
        $client->submit($form);

        //Vérifier le statut HTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects();
        $client->followRedirect();
        

        //Vérifier la présence du message 
        $this->assertSelectorTextContains('div.alert-success', "L'utilisateur a bien été ajouté.");
    }

    public function testRegisterIsInvalid(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('user_create'));

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');

        $faker = Factory::create();

        //Récupérer le formulaire 
        $submitButton = $crawler->selectButton('Register');
        $form = $submitButton->form();

        $form['registration_form[roles]']->setValue('ROLE_USER');
        $form['registration_form[username]'] = "adminTodo";
        $form['registration_form[plainPassword][first]'] = "Password123";
        $form['registration_form[plainPassword][second]'] = "Password123";
        $form['registration_form[email]'] = $faker->email();
        

        //Soumettre le formulaire 
        $client->submit($form);

        //Vérifier le statut HTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
        //Vérifier la présence du message 
        $this->assertSelectorTextContains('li', "Il existe déjà un compte avec ce nom d'utilisateur");
        
    }
}
