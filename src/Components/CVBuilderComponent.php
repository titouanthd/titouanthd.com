<?php
// cv_builder component

namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('cv_builder')]
class CVBuilderComponent
{
    use DefaultActionTrait;
}