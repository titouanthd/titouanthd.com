<?php

namespace App\Components\Atoms;

use App\Entity\Blog\Tag;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('atoms/tag_link')]
class TagLinkComponent
{
  public Tag $tag;
}
