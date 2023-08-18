<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity()
    {
        return (new User())
            ->setUsername('username #1')
            ->setPassword('password #2')
            ->setEmail('test@test.fr');
    }

    public function testUserEntityIsValid(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();
        $user = $this->getEntity();
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(0, $errors);
        
    }

    public function testUserEntityIsInvalid(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();
        $user = $this->getEntity();
        $user->setUsername('');
        $user->setEmail('');
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(2, $errors);
    }
}
