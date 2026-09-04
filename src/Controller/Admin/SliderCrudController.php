<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class SliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slider::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('title')->setColumns(12);
        yield TextField::new('subtitle')->setRequired(false)->setColumns(12);
        yield TextField::new('description')->setRequired(false)->setColumns(12);
        yield BooleanField::new('is_active')->setRequired(false)->setColumns(12);
        yield UrlField::new('url')->setRequired(false)->setColumns(12);

        yield ImageField::new('cover')->setColumns(12)
            ->setBasePath('uploads/sliders/') // The public URL path used to display the image
            ->setUploadDir('public/uploads/sliders/') // The relative system path where files are saved
            ->setRequired(false)
            ->setUploadedFileNamePattern('[slug]-[randomhash].[extension]');
    }

}
