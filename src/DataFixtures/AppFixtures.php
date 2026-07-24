<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(public UserPasswordHasherInterface $hasher)
    {
    }


    public function load(ObjectManager $manager): void
    {
        $hasAdmin = $manager->getRepository(Admin::class)->count();
        if ($hasAdmin <= 0) {

            $admin = new Admin();
            $admin->setName('Admin');
            $admin->setEmail('admin@admin.com');
            $password = $this->hasher->hashPassword($admin, 'password');
            $admin->setPhone('1246464');
            $admin->setPassword($password);


            $manager->persist($admin);

            $manager->flush();
        }
    }

    public static function getGroups(): array
    {
        return ['main'];
    }
}
