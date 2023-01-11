<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    // use constructor to inject dependencies password encoder
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        $hasher = $this->hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@localhost');
        $admin->setPseudo('admin');
        $admin->setRoles(['ROLE_ADMIN']);

        $password =  $this->hasher->hashPassword($admin, '0000');
        $admin->setPassword($password);

        // created at and updated
        $date = new \DateTime();
        $admin->setCreatedAt($date);
        $admin->setUpdatedAt($date);

        // set isVerified to true
        $admin->setIsVerified(true);

        $manager->persist($admin);
        // echo pseudo and create a new reference for this user
        echo $admin->getPseudo() . ' has been created' . PHP_EOL;
        $this->addReference('admin', $admin);

        for ( $i = 1; $i < 10; $i++ ) {
            $user = new User();
            $user->setEmail('user' . $i . '@localhost');
            $user->setPseudo('user' . $i);
            $user->setRoles(['ROLE_USER']);

            $password =  $this->hasher->hashPassword($user, '0000');
            $user->setPassword($password);

            // created at and updated
            $date = new \DateTime();
            $user->setCreatedAt($date);
            $user->setUpdatedAt($date);

            // set isVerified to true
            $user->setIsVerified(true);

            $manager->persist($user);
            // echo pseudo and create a new reference for this user
            echo $user->getPseudo() . ' has been created' . PHP_EOL;
            $this->addReference('user' . $i, $user);
        }

        $manager->flush();
    }
}
