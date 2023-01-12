<?php

namespace App\DataFixtures;

use App\Entity\Blog\Reply;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReplyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = [];
        for($i = 1; $i < 11; $i++) {
            $users[] = $this->getReference('user' . $i);
        }

        $comments = [];
        for($i = 1; $i < 301; $i++) {
            $comments[] = $this->getReference('comment' . $i);
        }

        for($i = 1; $i < 151; $i++) {
            $reply = new Reply();
            $reply->setContent('Reply ' . $i);
            $manager->persist($reply);

            // set author
            $reply->setAuthor($users[array_rand($users)]);

            // set comment
            $reply->setComment($comments[array_rand($comments)]);

            // set created at
            $date = new \DateTime();
            $reply->setCreatedAt($date);

            // echo pseudo and create a new reference for this user
            echo 'reply'.$i.' has been created' . PHP_EOL;
            $this->addReference('reply'.$i, $reply);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CommentFixtures::class,
        ];
    }
}
