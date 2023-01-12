<?php

namespace App\Components\Atoms;

use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('locale_selector', 'components/atoms/locale_selector.html.twig')]
class LocaleSelectorComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $locale = 'en';
    
    // private $router;

    // public function __construct(RouterInterface $router)
    // {
    //     $this->router = $router;
    // }

    // public function getLocale(): string
    // {
    //     return $this->router->getContext()->getParameter('_locale');
    // }

    // public function setLocal(string $locale): void
    // {
    //     $this->router->getContext()->setParameter('_locale', $locale);
    // }

    // public function getLocales(): array
    // {
    //     return ['en', 'fr'];
    // }
}
