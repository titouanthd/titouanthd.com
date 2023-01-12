<?php

namespace App\Components;

use App\Entity\Blog\Post;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('post_card')]
class PostCardComponent
{
  public Post $post;
}
