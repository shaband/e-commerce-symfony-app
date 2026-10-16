<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Type\ProductImageType;
use App\Form\Type\ProductVariantType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\EntityManagerInterface;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Product')->setEntityLabelInPlural('Products');
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab("Information General");
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name')->setColumns(12);
        yield AssociationField::new('category')->setColumns(12)->autocomplete();
        yield TextareaField::new('small_description', 'Short description')->setRequired(false)->setColumns(12);
        yield TextEditorField::new('description')->setRequired(false)->setColumns(12)->hideOnIndex();
        yield FormField::addTab("Images");

        yield CollectionField::new('productImages', 'Images')
            ->setEntryType(ProductImageType::class)
            ->setEntryIsComplex(true)
            ->onlyOnForms()
            ->setColumns(12);
        yield FormField::addTab("Variants");

        yield CollectionField::new('productVariants', 'Variants')
            ->setEntryType(ProductVariantType::class)
            ->setEntryIsComplex(true)
            ->onlyOnForms()
            ->setColumns(12);
    }

    public function persistEntity(EntityManagerInterface $entityManager, object $entityInstance): void
    {
        $entityInstance->normalizePrimaryImage();
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, object $entityInstance): void
    {
        $entityInstance->normalizePrimaryImage();
        parent::updateEntity($entityManager, $entityInstance);
    }
}
