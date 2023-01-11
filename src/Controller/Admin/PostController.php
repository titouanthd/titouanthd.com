<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\Admin\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/post', name: 'admin_post_')]
class PostController extends AbstractController
{
    #[Route('/index/', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $em, PaginatorInterface  $paginator, Request $request): Response
    {
        // get post config
        $dql   = "SELECT p FROM App\Entity\Post p ORDER BY p.id DESC";
        $query = $em->createQuery($dql);

        $currentPage = $request->query->getInt('page', 1);
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $limit = 50;
        if ($request->query->getInt('limit', 0) > 0) {
            $limit = $request->query->getInt('limit', 0);
        }

        $posts = $paginator->paginate(
            $query,
            $currentPage,
            $limit
        );

        return $this->render('admin/post/index.html.twig', [
            'posts' => $posts,
            'cols' => ['id', 'slug', 'status'],
            'limit' => $limit,
            'page_title' => 'Posts',
        ]);
    }

    #[Route('/new/', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository, UserPasswordHasherInterface $hasher): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // set updated_at and created_at
            $date = new \DateTime();
            $post->setUpdatedAt($date);
            $post->setCreatedAt($date);

            // set author
            $post->setAuthor($this->getUser());

            $postRepository->save($post, true);

            $this->addFlash('success', 'Post created successfully');

            return $this->redirectToRoute('admin_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'page_title' => 'New post',
        ]);
    }

    #[Route('/{id}/', name: 'show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('admin/post/show.html.twig', [
            'post' => $post,
            'page_title' => 'Show post',
        ]);
    }

    #[Route('/{id}/edit/', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // set updated_at
            $date = new \DateTime();
            $post->setUpdatedAt($date);

            $postRepository->save($post, true);

            $this->addFlash('success', 'Post updated successfully');

            return $this->redirectToRoute('admin_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'page_title' => 'Edit post',
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);

            $this->addFlash('success', 'Post deleted successfully');
        }

        return $this->redirectToRoute('admin_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
