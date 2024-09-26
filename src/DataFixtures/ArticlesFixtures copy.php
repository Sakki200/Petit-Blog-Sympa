<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private SluggerInterface $slugger) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $users[] = $this->getReference('user_' . $i);
        }
        $users[] = $this->getReference('user_admin');

        $articleCount = 0;
        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                $article = new Article();
                $article
                    ->setTitle($faker->words(5, true))
                    ->setContent($faker->randomHtml)
                    ->setAuthor($user);
                $manager->persist($article);

                // Add a reference to this note
                $this->addReference('note_' . $articleCount, $article);
                $articleCount++;
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
