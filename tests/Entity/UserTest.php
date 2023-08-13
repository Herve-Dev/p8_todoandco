<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $user = new User();

        $user->setUsername('testuser');
        $this->assertEquals('testuser', $user->getUsername());

        $user->setPassword('password');
        $this->assertEquals('password', $user->getPassword());

        $user->setRoles(['ROLE_ADMIN']);
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());

        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());
    }

    public function testAddAndRemoveTask()
    {
        $user = new User();
        $task1 = new Task();
        $task2 = new Task();

        $this->assertCount(0, $user->getTask());

        $user->addTask($task1);
        $this->assertCount(1, $user->getTask());
        $this->assertSame($user, $task1->getUser());

        $user->addTask($task2);
        $this->assertCount(2, $user->getTask());
        $this->assertSame($user, $task2->getUser());

        $user->removeTask($task1);
        $this->assertCount(1, $user->getTask());
        $this->assertNull($task1->getUser());
    }

}
