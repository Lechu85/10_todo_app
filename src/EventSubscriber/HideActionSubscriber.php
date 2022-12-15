<?php

namespace App\EventSubscriber;

use App\Entity\Question;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * event wywoływany jest nma każdej stronie Crud Contrtoler
 */
class HideActionSubscriber implements EventSubscriberInterface
{
    public function onBeforeCrudActionEvent(BeforeCrudActionEvent $event): void
    {
		//this not should be happen - ale zapobiegawczo
        if (!$adminContext = $event->getAdminContext()){
            return;
		}

		if (!$crudDto = $adminContext->getCrud()) {
			return;
		}

		if (!$crudDto->getEntityFqcn() !== Question::class) {
			return;
		}

		//info Disable action entirly for delete, detail and edit page
		$question = $adminContext->getEntity()->getInstance();
		if ($question instanceof Question && $question->getIsApproved()) {
			$crudDto->getActionsConfig()->disableActions([Action::DELETE]);
		}

		//return array of the actual actions that will ber enabled
	    //for the current page
		$actions = $crudDto->getActionsConfig()->getActions();
		if (!$deleteAction = $actions[Action::DELETE] ?? null) { //jesli nie ma akcji delete zwróć nic
			return;
		}

		//ale jeżeli jest
	    $deleteAction->setDisplayCallable(function(Question $question) {
			return !$question->getIsApproved();
	    });
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeCrudActionEvent::class => 'onBeforeCrudActionEvent',
        ];
    }
}
