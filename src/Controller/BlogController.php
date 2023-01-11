<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/blog', name: 'app_blog_')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PostRepository $pr): Response
    {
        // get the last post published 
        $lastPost = $pr->findOneBy(['status' => 'published'], ['created_at' => 'DESC']);

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'page_title' => 'Blog',
            'last_post' => $lastPost,
        ]);
    }
}
