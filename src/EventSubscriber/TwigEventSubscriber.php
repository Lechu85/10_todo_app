<?php

namespace App\EventSubscriber;

use App\Service\TaskCategoryMenu;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Twig\TwigFunction;

class TwigEventSubscriber implements EventSubscriberInterface
{
	private Environment $twig;
	private $cms_menu;
	private TaskCategoryMenu $taskCategoryMenu;

	public function __construct(Environment $twig, TaskCategoryMenu $taskCategoryMenu)
	{
		$taskCategoryMenu->generateTaskCategoryMenu();
		//info Enviroment jak chcemy dograc twiga

		$this->twig = $twig;

		$this->cms_menu = [
			'totolotek_index' => 'Dział 1',
			'home_index' => 'Dział 2',
		];

		//todo as admin array_push(); - dolaczyc linki adminas

	}

	public function onKernelController(ControllerEvent $event): void
    {

	    $this->twig->addGlobal('cms_menu', $this->cms_menu);

		//info tymczasowe rozwiazanie
	    $globals = $this->twig->getGlobals();
		if (!isset($globals['current_controller'])) {
			$this->twig->addGlobal('current_controller', '');//dopracować aktualna opacja w menu
		}


    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
