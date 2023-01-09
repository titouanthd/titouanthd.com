<?php
// header component

namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Routing\RouterInterface;

#[AsLiveComponent('header')]
class HeaderComponent
{
    use DefaultActionTrait;
}