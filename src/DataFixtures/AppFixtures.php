<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();

        for ($i = 0; $i < 4; $i++)
        {
            $article = new Article();
            $article->setTitle($faker->title);
            $article->setAuthor($faker->author);
            $article->setText('Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
              Proin sodales, arcu non commodo vulputate, neque lectus luctus metus, 
              ac hendrerit mi erat eu ante. Nullam blandit arcu erat,
              vitae pretium neque suscipit vitae. 
              Pellentesque sit amet lacus in metus placerat posuere. Aliquam hendrerit risus elit, non commodo nulla cursus id. 
              Vivamus tristique felis leo, vitae laoreet sapien eleifend vitae. Etiam varius sollicitudin tincidunt');
        }

        $manager->flush();
    }
}
