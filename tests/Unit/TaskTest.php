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

    public function testTaskEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $task = $this->getEntity();
        $errors = $container->get('validator')->validate($task);
        $this->assertCount(0, $errors);
    }

    public function testTaskInvalidTitleAndContent()
    {
        self::bootKernel();
        $container = static::getContainer();
        $task = $this->getEntity();
        $task->setTitle('');
        $task->setContent('');

        $errors = $container->get('validator')->validate($task);
        $this->assertCount(2, $errors);
    }
}
