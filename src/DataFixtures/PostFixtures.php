<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $admin = $this->getReference('admin');

        for($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->setTitle('Post n°' . $i);
            $post->setSlug('post-' . $i);
            $post->setFtImage('https://picsum.photos/1280/720');
            $post->setContent('Post content n°' . $i);
            $post->setStatus('published');
            $post->setAuthor($admin);
            // created at and updated
            $date = new \DateTime();
            $post->setCreatedAt($date);
            $post->setUpdatedAt($date);
            $manager->persist($post);
            // echo pseudo and create a new reference for this user
            echo $post->getTitle() . ' has been created' . PHP_EOL;
            $this->addReference($post->getSlug(), $post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
