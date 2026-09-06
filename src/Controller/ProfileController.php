<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangeUserPasswordType;
use App\Form\RegistrationFormType;
use App\Form\UpdateUserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile', methods: ['GET', 'PATCH'])]
    public function index(
        Request                     $request,
        #[CurrentUser] User         $user,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface      $entityManager
    ): Response
    {
        $changePasswordFrom = $this->createForm(ChangeUserPasswordType::class, $user, [
            'method' => 'PATCH',
        ]);
        $changePasswordFrom->handleRequest($request);

        if ($changePasswordFrom->isSubmitted() && $changePasswordFrom->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $changePasswordFrom->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $entityManager->persist($user);
            $entityManager->flush();
            flash()->success('Password updated successfully!');
        }
        if ($changePasswordFrom->isSubmitted() && !$changePasswordFrom->isValid()) {
            flash()->error('Operation Failed!');

        }


        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'changePasswordFrom' => $changePasswordFrom,
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit', methods: ['GET', 'PUT'])]
    public function edit(
        #[CurrentUser] User    $user,
        Request                $request,
        EntityManagerInterface $entityManager

    ): Response
    {
        $form = $this->createForm(UpdateUserProfileType::class, $user,[
            'method' => 'PUT',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);

            $entityManager->flush();
            flash()->success('Data updated successfully!');

        }
        if ($form->isSubmitted() && !$form->isValid()) {
            flash()->error('Validation failed. Please check the form for errors.');

        }
            return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
