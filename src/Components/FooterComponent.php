<?php
// header component

namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('footer')]
class FooterComponent
{
    use DefaultActionTrait;
}