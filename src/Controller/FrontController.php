<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// set default path for language switcher
// if no locale is specified, the default locale is used (en)
#[Route('', defaults: ['_locale' => 'en'], requirements: ['_locale' => 'en|fr'])]
class FrontController extends AbstractController
{
    // add new route on / to redirect to home with default locale
    #[Route('/', name: 'app_home_redirect')]
    public function homeRedirect(): Response
    {
        return $this->redirectToRoute('app_home', ['_locale' => 'en']);
    }

    #[Route('/{_locale}/', name: 'app_home')]
    public function home(Request $request): Response
    {
        return $this->render('front/home.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
    
    // add new route to access to my CV page
    #[Route('/{_locale}/documents/my-cv/', name: 'app_my_cv', methods: ['GET'])]
    public function cv(): Response
    {
        return $this->render('front/my-cv.html.twig');
    }

    // add new route to access to my CV page
    #[Route('/{_locale}/documents/my-covering-letter/', name: 'app_my_covering_letter', methods: ['GET'])]
    public function coverLetter(): Response
    {
        return $this->render('front/my-covering-letter.html.twig');
    }

    // create a profile page
    #[Route('/{_locale}/profile/', name: 'app_profile', methods: ['GET', 'POST'])]
    public function profile(TranslatorInterface $translator): Response
    {
        // check if user is logged in before rendering the page
        $isVerified = $this->getUser()->isVerified() ? true : false;

        // if is not verified, add flash message
        if (!$isVerified) {
            $this->addFlash('warning', 'Your account is not verified. Please check your email to verify your account.');
        }

        return $this->render('front/profile.html.twig');
    }
}
