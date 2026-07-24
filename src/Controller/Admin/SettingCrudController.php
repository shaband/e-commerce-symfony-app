<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW, Action::DELETE);
    }
    public function configureFields(string $pageName): iterable
    {
        // 1. Get the current entity instance
        $entity = $this->getContext()->getEntity()->getInstance();

        // 2. Always show the key name
        yield TextField::new('keyName', 'Setting Name')->setDisabled();

        // 3. Render the 'value' field conditionally based on the 'type' column
        if ($entity) {
            yield match ($entity->getType()) {
                'number' => IntegerField::new('value', 'Value'),
                'email' => EmailField::new('value', 'Value'),
                'toggle' => BooleanField::new('value', 'Enabled / Disabled'),
                'file' => ImageField::new('value', 'Uploaded File')
                    ->setBasePath('/uploads/settings')
                    ->setUploadDir('public/uploads/settings'),
                default => TextField::new('value', 'Value'), // handles 'text'
            };
        }
    }
}
