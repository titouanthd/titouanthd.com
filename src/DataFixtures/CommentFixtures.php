<?php

namespace App\DataFixtures;

use App\Entity\Blog\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = [];
        for($i = 1; $i < 11; $i++) {
            $users[] = $this->getReference('user' . $i);
        }

        $posts = [];
        for($i = 1; $i < 101; $i++) {
            $posts[] = $this->getReference('post' . $i);
        }

        for($i = 1; $i < 301; $i++) {
            $comment = new Comment();
            $comment->setContent('Comment ' . $i);

            // set author
            $comment->setAuthor($users[array_rand($users)]);

            // set post
            $comment->setPost($posts[array_rand($posts)]);

            // set created at
            $date = new \DateTime();
            $comment->setCreatedAt($date);
            $manager->persist($comment);

            // echo pseudo and create a new reference for this user
            echo 'comment'.$i.' has been created' . PHP_EOL;
            $this->addReference('comment'.$i, $comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PostFixtures::class,
        ];
    }
}
