<?php

namespace App\Controller\Admin;

use App\Entity\Testimonial;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TestimonialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Testimonial::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield TextareaField::new('description');
        yield BooleanField::new('is_active');
        yield ImageField::new('avatar')
            ->setBasePath('uploads/testimonial/avatars/') // The public URL path used to display the image
            ->setUploadDir('public/uploads/testimonial/avatars/') // The relative system path where files are saved
            ->setRequired(false)
            ->setUploadedFileNamePattern('[slug]-[randomhash].[extension]');
    }

}
