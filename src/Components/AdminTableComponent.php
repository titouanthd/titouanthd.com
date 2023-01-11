<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('admin_table')]
class AdminTableComponent
{
  public string $title = 'Table';

  public string $entity = 'user';

  public mixed $cols = null;

  public mixed $data = null;

  public int $limit = 5;
}
