<?php

namespace App\Controller\Admin;

use App\Entity\Sponsors;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SponsorsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sponsors::class;
    }


    public function configureFields(string $pageName): iterable
    {

        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield BooleanField::new('is_active');
        yield ImageField::new('logo')
            ->setBasePath('uploads/sponsors/logos/') // The public URL path used to display the image
            ->setUploadDir('public/uploads/sponsors/logos/') // The relative system path where files are saved
            ->setRequired(false)
            ->setUploadedFileNamePattern('[slug]-[randomhash].[extension]');

    }

}
