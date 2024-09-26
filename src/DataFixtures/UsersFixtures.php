<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        // Create admin user
        $admin = new User();
        $admin
            ->setEmail('testesADM@gmail.com')
            ->setUsername('testesADM')
            ->setPassword($this->hasher->hashPassword($admin, 'testes'))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $this->addReference('user_admin', $admin);

        // Create regular users
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->email)
                ->setUsername($faker->userName)
                ->setPassword($this->hasher->hashPassword($user, 'userpassword'))
                ->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);

        }

        $manager->flush();
    }
}
