<?php

namespace App\DataFixtures;

use App\Entity\Blog\Reaction;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReactionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = [];
        for($i = 1; $i < 11; $i++) {
            $users[] = $this->getReference('user' . $i);
        }

        $posts = [];
        for($i = 1; $i < 11; $i++) {
            $posts[] = $this->getReference('post' . $i);
        }

        $comments = [];
        for($i = 1; $i < 301; $i++) {
            $comments[] = $this->getReference('comment' . $i);
        }

        $replies = [];
        for($i = 1; $i < 151; $i++) {
            $replies[] = $this->getReference('reply' . $i);
        }

        $types = ['like', 'save'];

        for($i = 1; $i < 1001; $i++) {
            $reaction = new Reaction();

            // set type
            $reaction->setType($types[array_rand($types)]);

            // choose between posts, comments or replies
            $choose = rand(1, 3);
            switch ($choose) {
                case 1:
                    $reaction->setPost($posts[array_rand($posts)]);
                    break;
                case 2:
                    $reaction->setComment($comments[array_rand($comments)]);
                    break;
                case 3:
                    $reaction->setReply($replies[array_rand($replies)]);
                    break;
            }

            // set author
            $reaction->setUser($users[array_rand($users)]);

            // set created at
            $date = new \DateTime();
            $reaction->setCreatedAt($date);
            $manager->persist($reaction);

            // echo pseudo and create a new reference for this user
            echo 'reaction'.$i.' has been created' . PHP_EOL;
            $this->addReference('reaction'.$i, $reaction);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PostFixtures::class,
            CommentFixtures::class,
            ReplyFixtures::class,
        ];
    }
}
