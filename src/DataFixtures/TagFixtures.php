<?php

namespace App\DataFixtures;

use App\Entity\Blog\Tag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TagFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        // ok now we will create 30 tags
        // by using th public/data/tags.csv file
        $tags = array_map('str_getcsv', file('public/data/tags.csv'));
        // remove the first line (the header)
        array_shift($tags);
        // loop over the array
        $loop = 1;
        foreach ($tags as $tag) {
            $tagEntity = new Tag();
            $tagEntity->setName($tag[0]);
            $tagEntity->setDescription($tag[1]);
            $tagEntity->setSlug($tag[2]);
            $manager->persist($tagEntity);
            // echo pseudo and create a new reference for this user
            echo $tag[0] . ' has been created' . PHP_EOL;
            $this->addReference('tag' . $loop, $tagEntity);
            $loop++;
        }

        $manager->flush();
    }
}
