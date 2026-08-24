<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminCrudController extends AbstractCrudController
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private RequestStack                $requestStack,
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Admin::class;
    }


    public function configureFields(string $pageName): iterable
    {

        yield IdField::new('id')->hideOnForm();
        yield EmailField::new('email')->setColumns(12);
        yield TextField::new('name')->setColumns(12);
        yield TextField::new('phone')->setColumns(12);

        yield TextField::new("password")
            ->onlyOnForms()
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
                'invalid_message' => 'The password fields must match.',
                'mapped' => false,
            ])->setColumns(12);
        yield ImageField::new('avatar')
            ->setBasePath('uploads/avatars/') // The public URL path used to display the image
            ->setUploadDir('public/uploads/avatars/') // The relative system path where files are saved
            ->setRequired(false)
            ->setUploadedFileNamePattern('[slug]-[randomhash].[extension]')
            ->onlyOnForms()->setColumns(12);


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
}
