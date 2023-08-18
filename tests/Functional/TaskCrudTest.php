<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskCrudTest extends WebTestCase
{
    Public Function testIfListTaskOfUserConnectedIsSuccessful(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 64);
        $client->loginUser($user);

        $client->request(Request::METHOD_GET, $urlGenerator->generate('task_list'));
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list');
    }

    public function testIfTaskIsCreateWithUserAssigned(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 64);
        $client->loginUser($user);

        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('task_create'));
        $form = $crawler->filter('form[name=task]')->form([
            'task[title]' => 'testFonctionel Titre',
            'task[content]' => 'testFonctionel Contenu'
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains("div.alert-success", "La tâche a été bien été ajoutée.");

    }

    public function testIfTaskIsModifiedWithSuccessful(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 64);
        $task = $entityManager->getRepository(Task::class)->findOneBy([
            'user' => $user
        ]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('task_edit', ['id' => $task->getId()])
        );

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name=task]')->form([
            'task[title]' => 'testFonctionel Titre modifié',
            'task[content]' => 'testFonctionel Contenu modifié'
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains("div.alert-success", "La tâche a bien été modifiée.");
    }

    public function testIfTaskIsDeletedWithSuccessful(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 64);
        $task = $entityManager->getRepository(Task::class)->findOneBy([
            'user' => $user
        ]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('task_delete', ['id' => $task->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains("div.alert-success", "La tâche a bien été supprimée.");

        $this->assertRouteSame('task_list');
    }
}
