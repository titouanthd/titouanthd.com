<?php
// header component

namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('default_footer')]
class FooterComponent
{
    use DefaultActionTrait;
}