<?php

namespace App\EventSubscriber;

use App\Services\HeaderService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class HeaderSubscriber implements EventSubscriberInterface
{
    private $headerService;
    private $twig;

    public function __construct(HeaderService $headerService, Environment $twig)
    {
        $this->headerService = $headerService;
        $this->twig = $twig;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $headerData = $this->headerService->getHeaderData();

        $this->twig->addGlobal('headerData', $headerData);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
