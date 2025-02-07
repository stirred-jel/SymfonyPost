<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class AuthController extends AbstractController
{
    #[Route('/signin', name: 'user_login')]
    public function login(AuthenticationUtils $authUtils): Response
    {
        $errorMessage = $authUtils->getLastAuthenticationError();
        $lastUser = $authUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_user' => $lastUser,
            'error_message' => $errorMessage,
        ]);
    }
}
