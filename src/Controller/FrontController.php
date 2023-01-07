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
    #[Route('/my-cv', name: 'app_my_cv', methods: ['GET'])]
    public function cv(): Response
    {
        return $this->render('front/my-cv.html.twig');
    }

    // 

    // // create new route to access CV-builder page
    // #[Route('/cv-builder', name: 'app_cv_builder', methods: ['GET', 'POST'])]
    // public function cvBuilder(Request $request): Response
    // {
    //     // check if user is logged in before rendering the page
    //     $isLogged = $this->getUser() ? true : false;
    //     if (!$this->getUser()) {
    //         // add flash message
    //         $this->addFlash('danger', 'You must be logged in to access this page. Please <a href="/login">sign in</a> or <a href="/register">register</a>.');
    //     }

    //     // check is user have a linkedin profile
    //     $linkedinProfile = $this->getUser() ? $this->getUser()->getLinkedin() : null;
    //     if (!$linkedinProfile) {
    //         // add flash message
    //         $this->addFlash('danger', 'You must have a linkedin profile to access this page. Please <a href="/profile">add your linkedin profile</a>.');
    //     }

    //     return $this->render('front/cv-builder/index.html.twig', [
    //         'isLogged' => $isLogged,
    //     ]);
    // }

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
