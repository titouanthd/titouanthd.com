<?php

namespace App\Components\Atoms;

use App\Entity\Blog\Post;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('atoms/add_comment')]
class AddCommentComponent
{
  public Post $post;

  public function getCountCommentsWithReplies(): int
  {
    $comments = $this->post->getComments();
    $countComments = count($comments);

    if ($countComments === 0) {
      return 0;
    }

    $countCommentWithReplies = $countComments;
    foreach ($comments as $comment) {
      $replies = $comment->getReplies();
      $countReplies = count($replies);
      $countCommentWithReplies += $countReplies;
    }
    return $countCommentWithReplies;
  }

  public function getLabel()
  {
    $countComments = $this->getCountCommentsWithReplies  ();
    if ($countComments === 0) {
      return 'Add comment';
    }
    if ($countComments === 1) {
      return '1 Comment';
    }
    return $countComments . ' Comments';
  }

  public function getHref()
  {
    return '/blog/' . $this->post->getSlug() . '/#add-comment';
  }
}
