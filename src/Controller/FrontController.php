<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('front/home.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
}
