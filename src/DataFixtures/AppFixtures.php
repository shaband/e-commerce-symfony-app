<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(public UserPasswordHasherInterface $hasher)
    {
    }


    public function load(ObjectManager $manager): void
    {
        $admin = new Admin();
        $admin->setName('Admin');
        $admin->setEmail('admin@admin.com');
        $password = $this->hasher->hashPassword($admin, 'password');
        $admin->setPhone('1246464');
        $admin->setPassword($password);


        // $product = new Product();
         $manager->persist($admin);

        $manager->flush();
    }
}
