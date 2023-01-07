<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('front/home.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
    
    // add new route to access to my CV page
    #[Route('/documents/my-cv', name: 'app_my_cv', methods: ['GET'])]
    public function cv(): Response
    {
        return $this->render('front/my-cv.html.twig');
    }

    // add new route to access to my CV page
    #[Route('/documents/my-covering-letter', name: 'app_my_covering_letter', methods: ['GET'])]
    public function coverLetter(): Response
    {
        return $this->render('front/my-covering-letter.html.twig');
    }

    // create a profile page
    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request): Response
    {
        // check if user is logged in before rendering the page
        $isLogged = $this->getUser() ? true : false;
        if (!$this->getUser()) {
            // add flash message
            $this->addFlash('danger', 'You must be logged in to access this page. Please <a href="/login">sign in</a> or <a href="/register">register</a>.');
        }

        return $this->render('front/profile.html.twig', [
            'isLogged' => $isLogged,
        ]);
    }
}
