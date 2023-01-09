<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/{_locale}/login/', name: 'app_login', defaults: ['_locale' => 'en'], requirements: ['_locale' => 'en|fr'])]
    public function login(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator): Response
    {
        // if user is already logged in, redirect to home and add a flash message
        $isLogged = $this->getUser() ? true : false;

        if ($isLogged) {
            $translatedMessage = $translator->trans('You are already logged in, you can\'t access this page. If you want to register a new account, please <a href="/logout">logout first</a>.');
            $this->addFlash('warning', $translatedMessage);

            return $this->redirectToRoute('app_profile');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'error' => $error, 
        ]);
    }

    #[Route(path: '/logout/', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
