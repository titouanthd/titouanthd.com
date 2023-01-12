<?php

namespace App\Controller;

use App\Entity\Blog\Tag;
use App\Entity\Blog\Post;
use App\Repository\Blog\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/blog', name: 'app_blog_', defaults: ['_locale' => 'fr'], requirements: ['_locale' => 'en|fr'])]
class BlogController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function relevant(EntityManagerInterface $em, PostRepository $pr, PaginatorInterface $paginator, Request $request): Response
    {
        // get relevant posts published paginated
        $relevantQuery = $em->createQuery("SELECT p FROM App\Entity\Blog\Post p WHERE p.status = 'published' AND p.relevant = true ORDER BY p.created_at DESC");

        $relevant = $paginator->paginate(
            $relevantQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('blog/index.html.twig', [
            'page_title' => 'Blog',
            'listing' => $relevant,
        ]);
    }

    #[Route('/latest', name: 'latest')]
    public function latest(EntityManagerInterface $em, PostRepository $pr, PaginatorInterface $paginator, Request $request): Response
    {
        // get the latest posts published paginated
        $latestQuery = $em->createQuery("SELECT p FROM App\Entity\Blog\Post p WHERE p.status = 'published' ORDER BY p.created_at DESC");

        $latest = $paginator->paginate(
            $latestQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('blog/index.html.twig', [
            'page_title' => 'Blog',
            'listing' => $latest,
        ]);
    }

    #[Route('/top', name: 'top')]
    public function top(EntityManagerInterface $em, PostRepository $pr, PaginatorInterface $paginator, Request $request): Response
    {
        // get top posts (most reactions) published paginated
        $topQuery = $em->createQuery("SELECT p, COUNT(r) as reactions FROM App\Entity\Blog\Post p LEFT JOIN p.reactions r WHERE p.status = 'published' GROUP BY p ORDER BY reactions DESC");

        $top = $paginator->paginate(
            $topQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('blog/index.html.twig', [
            'page_title' => 'Blog',
            'listing' => $top,
        ]);
    }

    // show a blog post
    #[Route('/{slug}/', name: 'show_post')]
    public function showPost(Post $post): Response
    {
        // count like reactions
        $likes = 0;
        foreach ($post->getReactions() as $reaction) {
            if ($reaction->getType() == 'like') {
                $likes++;
            }
        }

        // count save reactions
        $saves = 0;
        foreach ($post->getReactions() as $reaction) {
            if ($reaction->getType() == 'save') {
                $saves++;
            }
        }

        // count comments with replies
        $comments = 0;
        foreach ($post->getComments() as $comment) {
            $comments++;
            foreach ($comment->getReplies() as $reply) {
                $comments++;
            }
        }

        return $this->render('blog/show_post.html.twig', [
            'page_title' => 'Blog',
            'post' => $post,
            'likes' => $likes,
            'saves' => $saves,
            'comments' => $comments,
        ]);
    }

    // show a blog post
    #[Route('/tag/{slug}/', name: 'show_tag')]
    public function showTag(Tag $tag): Response
    {
        return $this->render('blog/show_tag.html.twig', [
            'page_title' => 'Blog',
            'post' => $tag,
        ]);
    }
}
