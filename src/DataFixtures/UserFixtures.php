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
        $admin->setLinkedin('https://www.linkedin.com/in/titouanthd/');

        $password =  $this->hasher->hashPassword($admin, '0000');
        $admin->setPassword($password);

        // set isVerified to true
        $admin->setIsVerified(true);

        $manager->persist($admin);
        // echo pseudo and create a new reference for this user
        echo $admin->getPseudo() . ' has been created' . PHP_EOL;
        $this->addReference('admin', $admin);

        $manager->flush();
    }
}
