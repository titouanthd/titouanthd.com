<?php

namespace App\Components\Atoms;

use App\Entity\Blog\Post;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('atoms/add_reaction')]
class AddReactionComponent
{
  public Post $post;

  public function getCountReactions(): int
  {
    $reactions = $this->post->getReactions();
    $countReactions = count($reactions);

    return $countReactions;
  }

  public function getLabel()
  {
    $countReactions = $this->getCountReactions();
    if ($countReactions === 0) {
      return 'Add reaction';
    }
    if ($countReactions === 1) {
      return '1 Reaction';
    }
    return $countReactions . ' Reactions';
  }

  public function getHref()
  {
    return '/blog/' . $this->post->getSlug() . '/#add-reaction';
  }
}
