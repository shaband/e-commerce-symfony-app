<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Enum\GenderEnum;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FileField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private RequestStack                $requestStack,
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab("Information General");
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('full_name')->hideOnForm();
        yield TextField::new('first_name')->onlyOnForms();
        yield TextField::new('last_name')->onlyOnForms();
        yield TextField::new('email');
        yield TextField::new('phone');

        yield ChoiceField::new('gender')->setChoices(GenderEnum::cases());
        yield DateField::new('birthdate')->renderAsChoice();

        yield FormField::addTab("Password");
        yield TextField::new('password')->setFormType(RepeatedType::class)->setFormTypeOptions([
            'type' => PasswordType::class,
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Confirm Password'],
            'invalid_message' => 'The password fields must match.',
            'mapped' => false,
        ])->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms();
        yield FormField::addTab("Avatar");
        yield ImageField::new('avatar')
            ->setBasePath('uploads/avatars/') // The public URL path used to display the image
            ->setUploadDir('public/uploads/avatars/') // The relative system path where files are saved
            ->setRequired(false)
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->onlyOnForms();

    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param User $entityInstance
     * @return void
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $plainPassword = $request->request->all()['User']['password']['first'] ?? null;

        if ($plainPassword) {
            $entityInstance->setPassword(
                $this->passwordHasher->hashPassword($entityInstance, $plainPassword)
            );
        }
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(ChoiceFilter::new('gender')
            ->setChoices([
                    'Male' => GenderEnum::Male,
                    'Female' => GenderEnum::Female,
                ]
            ));
    }
}
