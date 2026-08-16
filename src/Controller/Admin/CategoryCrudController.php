<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Enum\CategoryGroupEnum;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Requirement\EnumRequirement;

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

        yield ChoiceField::new('category_group')->setChoices(CategoryGroupEnum::cases())->setFormTypeOption('attr', [
            'hx-get' => '/admin/category/get-categories',
            'hx-trigger' => "change",
            'hx-swap'=>'innerHTML',
            'hx-target' => '.parent_category',

        ]);

        yield TextEditorField::new('description')->setRequired(false);


        yield ChoiceField::new(propertyName: 'parent', label: 'Parent Category')
            ->setRequired(false)
            ->addCssClass('parent_category')
            ->setChoices([])
            ->renderAsNativeWidget()
            ->formatValue(fn($value, Category $entity) => $entity->getParent()?->getName());

        yield ImageField::new('cover')->setBasePath('uploads/avatars/') // The public URL path used to display the image
        ->setUploadDir('public/uploads/categories/') // The relative system path where files are saved
        ->setRequired(false)
            ->setUploadedFileNamePattern('[slug]-[randomhash].[extension]')
            ->onlyOnForms();;

    }

    #[AdminRoute(
        path: 'get-categories/',
        name: 'get_categories',
        options: [
            'methods' => ['GET'],
        ])]
    public function getCategories(
        CategoryRepository                           $categoryRepository,
        #[MapQueryParameter(name: 'Category')] array $category = [],
    ): Response
    {

        $groupValue = $category['category_group'] ?? null;

        if ($groupValue === null) {
            throw new BadRequestHttpException('Missing query parameter "Category[category_group]"');
        }

        $categoryType = CategoryGroupEnum::tryFrom($groupValue)
            ?? throw new BadRequestHttpException(sprintf('Invalid category_group "%s"', $groupValue));

        $collection = new ArrayCollection($categoryRepository->findBy(criteria: ['category_group' => $categoryType->getParentCategory()?->value]));
        $collection->reduce(func: function (array $accumulator, Category $item) {

            $accumulator[$item->getId()] = $item->getName();
            return $accumulator;
        }, initial: []);

        return $this->render('admin/category/_parent_category.html.twig', ['categories' => $collection->toArray()]);
    }


}
