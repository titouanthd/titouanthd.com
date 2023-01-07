<?php
// flashes component

namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('flashes')]
class FlashesComponent
{
    use DefaultActionTrait;
}