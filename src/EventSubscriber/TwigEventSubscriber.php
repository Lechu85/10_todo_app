<?php

namespace App\EventSubscriber;

use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
	private Environment $twig;
	private $cms_menu;

	public function __construct(Environment $twig)
	{

		$this->twig = $twig;

		$this->cms_menu = [
			'home_index' => 'Główna',
			'app_task_show_list' => 'Zadania',
			'totolotek_index' => 'Losuj',
			//'link3' => 'Dział 3'
		];

		//todo as admin array_push(); - dolaczyc linki adminas

	}

	public function onKernelController(ControllerEvent $event): void
    {
	    //info Teraz możesz dodać dowolną liczbę kontrolerów: zmienna conferences będzie zawsze dostępna w szablonach Twig.
	    $this->twig->addGlobal('cms_menu', $this->cms_menu);

    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
