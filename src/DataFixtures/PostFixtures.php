<?php

namespace App\DataFixtures;

use App\Entity\Blog\Post;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $admin = $this->getReference('admin');

        // get tag reference from 1 to 20
        for ($i = 1; $i < 31; $i++) {
            $tags[] = $this->getReference('tag' . $i);
        }

        for($i = 1; $i < 101; $i++) {
            $post = new Post();
            $post->setTitle('Post ' . $i);
            $post->setSlug('post-' . $i);
            $post->setFtImage('https://picsum.photos/854/267');
            $post->setContent('Post content n°' . $i);
            $post->setStatus('published');
            $post->setAuthor($admin);
            // created at and updated
            $date = new \DateTime('+' . $i . ' days');
            $post->setCreatedAt($date);
            $post->setUpdatedAt($date);
            // set reading_time
            $post->setReadingTime(5);
            // set random relevant
            $post->setRelevant(rand(0, 1));

            // set 4 random tags for this post
            $randomTags = array_rand($tags, 4);
            foreach ($randomTags as $randomTag) {
                $post->addTag($tags[$randomTag]);
            }
            $manager->persist($post);
            // echo pseudo and create a new reference for this user
            echo 'post'.$i.' has been created' . PHP_EOL;
            $this->addReference('post'.$i, $post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TagFixtures::class,
        ];
    }
}
