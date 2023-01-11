<?php

namespace App\Controller\Admin;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(['/admin'], name: 'admin_')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UserRepository $ur, PostRepository $pr): Response
    {
        // count users
        $countUsers = $ur->count([]);
        // count posts
        $countPosts = $pr->count([]);


        return $this->render('admin/index.html.twig', [
            'page_title' => 'Dashboard',
            'countUsers' => $countUsers,
            'countPosts' => $countPosts,
        ]);
    }
}
