<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminTest extends WebTestCase
{
    public function testAdminDeleteTaskAnonymousUser(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $admin = $entityManager->find(User::class, 1);
        $userAnonymous = $entityManager->find(User::class, 6);
     
        $task = $entityManager->getRepository(Task::class)->findOneBy([
            'user' => $userAnonymous
        ]);

        if (!$task) {
            $this->fail("Tâche non trouvée pour l'utilisateur anonyme.");
        }

        $client->loginUser($admin);

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('task_delete', ['id' => $task->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains("div.alert-success", "La tâche a bien été supprimée.");
        $this->assertRouteSame('task_list');
    }

    public function testAdminReadUserRegister(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $admin = $entityManager->find(User::class, 1);
        $client->loginUser($admin);

        $client->request(Request::METHOD_GET, $urlGenerator->generate('user_list'));
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('user_list');
    }

    public function testAdminModifyRoleUser(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get("router");
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $admin = $entityManager->find(User::class, 1);
        $user = $entityManager->getRepository(User::class)->findOneBy([
            'id' => 6
        ]);

        $client->loginUser($admin);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('user_edit', ['id' => $user->getId()])
        );

        $this->assertResponseIsSuccessful();

        $submitButton = $crawler->selectButton('Register');
        $form = $submitButton->form();
        $form['edit_role_form[roles]']->setValue('ROLE_ADMIN');

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertSelectorTextContains("div.alert-success", "L'utilisateur a bien été modifié");
    }
}
