<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\Article;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 500; $i++)
        {
            $article = new Article();

            $sentence = $faker->sentence(4);
            $title = substr($sentence, 0, strlen($sentence) - 1);
            $article->setTitle($title)
                    ->setAuthor($faker->name())
                    ->setContent($faker->text(3000))
                    ->setCreatedAt($faker->dateTimeThisYear());

            $manager->persist($article);
        }

        $manager->flush();
    }
}
