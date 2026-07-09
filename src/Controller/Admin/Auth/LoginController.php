<?php

namespace App\Controller\Admin\Auth;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


final class LoginController extends AbstractController
{
    #[AdminRoute('/login',  name: 'login')]
     public function index(AuthenticationUtils $authenticationUtils): Response
{
             // get the login error if there is one
             $error = $authenticationUtils->getLastAuthenticationError();

             // last username entered by the user
             $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/auth/login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
            ]);
    }
}
