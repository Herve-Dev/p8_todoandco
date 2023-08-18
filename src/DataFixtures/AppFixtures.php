<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
    ){}

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');

        //admin
        $admin = new User();
        $admin
            ->setUsername('adminTodo')
            ->setEmail($faker->email())
            ->setPassword($this->passwordEncoder->hashPassword($admin, 'Password123'))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        //users
        $users = [];
        for ($i = 1; $i <= 4; $i++) { 
            $user = new User();
            $user
                ->setUsername('user'. $i)
                ->setEmail($faker->email())
                ->setPassword($this->passwordEncoder->hashPassword($user, 'Password123'))
                ->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $users[] = $user; // Stocker les utilisateurs pour une utilisation ultérieure
        }

        //anonymes users
        $anonymousUser = new User();
        $anonymousUser
            ->setUsername("user [deleted]")
            ->setEmail("email [deleted]")
            ->setPassword($this->passwordEncoder->hashPassword($anonymousUser, 'Password123'))
            ->setRoles(['ROLE_ANONYMOUS']);
        $manager->persist($anonymousUser);
        
        

        // Tâches pour utilisateurs
        foreach ($users as $user) {
            for ($i = 1; $i <= 5; $i++) {
                $task = new Task();
                $task
                    ->setTitle("Tâche $i pour l'utilisateur " . $user->getUsername())
                    ->setContent("Contenu de la tâche $i pour l'utilisateur " . $user->getUsername())
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setIsDone(false)
                    ->setUser($user);
                $manager->persist($task);
            }
        }

        // Tâches pour admin
        for ($i = 1; $i <= 5; $i++) {
            $task = new Task();
            $task
                ->setTitle("Tâche Admin $i")
                ->setContent("Contenu de la tâche Admin $i")
                ->setCreatedAt(new \DateTimeImmutable())
                ->setIsDone(false)
                ->setUser($admin);
            $manager->persist($task);
        }

        // Tâches pour anonymeUsers
        for ($i = 1; $i <= 5; $i++) {
            $task = new Task();
            $task
                ->setTitle("Tâche $i pour " . $anonymousUser->getUsername())
                ->setContent("Contenu de la tâche $i pour " . $anonymousUser->getUsername())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setIsDone(false)
                ->setUser($anonymousUser);
            $manager->persist($task);
        }

        $manager->flush();
    }
}
