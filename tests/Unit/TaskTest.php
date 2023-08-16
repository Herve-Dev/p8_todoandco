<?php

namespace App\Tests\Unit;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function getEntity()
    {
        $user = new User();
        
        return (new Task())
            ->setTitle('title #1')
            ->setContent('content #2')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setIsDone(false)
            ->setUser($user);
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $task = $this->getEntity();
        $errors = $container->get('validator')->validate($task);
        $this->assertCount(0, $errors);
    }

    public function testInvalidTitle()
    {
        self::bootKernel();
        $container = static::getContainer();
        $task = $this->getEntity();
        $task->setTitle('');
        $task->setContent('');

        $errors = $container->get('validator')->validate($task);
        $this->assertCount(2, $errors);
    }

    public function testUserAssociation()
    {
        self::bootKernel();
        $container = static::getContainer();
        $task = $this->getEntity();
        
        $user = new User(); 
        $task->setUser($user);

        $errors = $container->get('validator')->validate($task);
        $this->assertCount(0, $errors);
    }

    public function testUserNotAssociation()
    {
        self::bootKernel();
        $container = static::getContainer();
        $task = $this->getEntity();
        $task->setUser(null);
        $errors = $container->get('validator')->validate($task);
        $this->assertCount(1, $errors);
    }
}
