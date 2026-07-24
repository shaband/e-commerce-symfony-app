<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $data = [
            [
                'name' => 'AppName',
                'type' => 'text',
                'value' => 'ShoppingApp',
            ],
            [
                'name' => 'Email',
                'type' => 'email',
                'value' => 'shoppingApp@mail.com',
            ],
            [
                'name' => 'phone',
                'type' => 'text',
                'value' => '0125862552',
            ],
        ];
        foreach ($data as $datum) {
            $setting = new Setting();
            $setting->setKeyName($datum['name']);
            $setting->setType($datum['type']);
            $setting->setValue($datum['value']);


            $manager->persist($setting);
        }
        $manager->flush();
    }
}
