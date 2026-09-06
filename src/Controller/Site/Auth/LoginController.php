<?php

namespace App\Controller\Site\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
     public function index(AuthenticationUtils $authenticationUtils): Response
{
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

             // get the login error if there is one
             $error = $authenticationUtils->getLastAuthenticationError();

             // last username entered by the user
             $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('site/auth/login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
            ]);
    }
}
