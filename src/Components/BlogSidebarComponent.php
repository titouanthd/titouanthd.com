<?php

namespace App\Components;

use App\Entity\Blog\Post;
use App\Repository\Blog\TagRepository;
use App\Repository\Blog\PostRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('blog_sidebar')]
class BlogSidebarComponent
{
  private TagRepository $tagRepository;

  public function __construct(TagRepository $tagRepository)
  {
    $this->tagRepository = $tagRepository;
  }

  public function getTags(): array
  {
    return $this->tagRepository->findBy([], ['name' => 'ASC']);
  }
}
