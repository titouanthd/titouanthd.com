<?php
// header component

namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('header')]
class HeaderComponent
{
    use DefaultActionTrait;
}