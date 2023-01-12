<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_home', defaults: ['_locale' => 'en'], requirements: ['_locale' => 'en|fr'])]
    public function home(): Response
    {
        return $this->render('front/home.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    // add new route to access to my CV page
    #[Route('/documents/my-cv/', name: 'app_my_cv', methods: ['GET'], defaults: ['_locale' => 'en'], requirements: ['_locale' => 'en|fr'])]
    public function cv(): Response
    {
        return $this->render('front/my-cv.html.twig');
    }

    // add new route to access to my CV page
    #[Route('/documents/my-covering-letter/', name: 'app_my_covering_letter', methods: ['GET'], defaults: ['_locale' => 'en'], requirements: ['_locale' => 'en|fr'])]
    public function coverLetter(): Response
    {
        return $this->render('front/my-covering-letter.html.twig');
    }

    // create a profile page
    #[Route('/profile/', name: 'app_profile', methods: ['GET', 'POST'], defaults: ['_locale' => 'en'], requirements: ['_locale' => 'en|fr'])]
    public function profile(TranslatorInterface $translator): Response
    {
        $user = $this->getUser();
        $isVerified = $user->isVerified() ? true : false;

        $message = $translator->trans('Your account is not verified. Please check your email to verify your account.');
        $linkLabel = $translator->trans('Resend verification email');

        // if is not verified, add flash message
        if (!$isVerified) {
            $this->addFlash('warning', $message . '&nbsp;<a href="/resend-verification-link/'.$user->getId().'/">' . $linkLabel . '</a>');
        }

        return $this->render('front/profile.html.twig');
    }
}
