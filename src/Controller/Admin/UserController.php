<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/user', name: 'admin_user_')]
class UserController extends AbstractController
{
    #[Route('/index/', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $em, PaginatorInterface  $paginator, Request $request): Response
    {
        // get user config
        $dql   = "SELECT u FROM App\Entity\User u ORDER BY u.id DESC";
        $query = $em->createQuery($dql);

        $currentPage = $request->query->getInt('page', 1);
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $limit = 50;
        if ($request->query->getInt('limit', 0) > 0) {
            $limit = $request->query->getInt('limit', 0);
        }

        $users = $paginator->paginate(
            $query,
            $currentPage,
            $limit
        );

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'cols' => ['id', 'email', 'roles'],
            'limit' => $limit,
            'page_title' => 'Users',
        ]);
    }

    #[Route('/new/', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get form data
            $data = $form->getData();

            // set updated_at and created_at
            $date = new \DateTime();
            $user->setUpdatedAt($date);
            $user->setCreatedAt($date);

            // set password
            $user->setPassword($hasher->hashPassword($user, $data->getPassword()));

            // save role
            $user->setRoles($data->getRoles());

            $userRepository->save($user, true);

            $this->addFlash('success', 'User created successfully');

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'page_title' => 'New user',
        ]);
    }

    #[Route('/{id}/', name: 'show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
            'page_title' => 'Show user',
        ]);
    }

    #[Route('/{id}/edit/', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // set updated_at
            $date = new \DateTime();
            $user->setUpdatedAt($date);

            $userRepository->save($user, true);

            $this->addFlash('success', 'User updated successfully');

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'page_title' => 'Edit user',
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);

            $this->addFlash('success', 'User deleted successfully');
        }

        return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
