<?php
// header component

namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('default_header')]
class HeaderComponent
{
    use DefaultActionTrait;
}