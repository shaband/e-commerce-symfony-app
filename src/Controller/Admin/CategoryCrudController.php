<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;

class CategoryCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Category::class;
    }



    public function configureFields(string $pageName): iterable
    {


        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield BooleanField::new('is_active')->hideOnForm();
        yield BooleanField::new("is_parent")->setFormTypeOptions([
            'attr' => ['onChange' => 'toggleParentChoice(this.checked)'],
        ]);
        yield TextEditorField::new('description')->setRequired(false);


        yield AssociationField::new('parent')->setCssClass('parent_choice')->setFormTypeOptions([
            'attr' => ['data-field-target' => 'parent_category_choice'],
        ])
            ->formatValue(fn($value, Category $entity) => $entity->getParent()?->getName())
            ->setQueryBuilder(
                queryBuilderCallable: fn(QueryBuilder $queryBuilder): QueryBuilder => $queryBuilder
                    ->andWhere('entity.is_parent = :is_parent')
                    ->setParameter('is_parent', true)
            )
            ->autocomplete(callback: static fn(Category $c): string => $c->getName())->setRequired(false);
        yield ImageField::new('cover')->setBasePath('uploads/avatars/') // The public URL path used to display the image
        ->setUploadDir('public/uploads/categories/') // The relative system path where files are saved
        ->setRequired(false)
            ->setUploadedFileNamePattern('[slug]-[randomhash].[extension]')
            ->onlyOnForms();;

    }

}
