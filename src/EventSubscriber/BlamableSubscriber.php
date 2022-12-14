<?php

namespace App\EventSubscriber;

use App\Entity\Question;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Subscriber ma za zadanie uakdualnienie przy kaqżdym update, kto zaktualizował pozycje w Encji Question
 * kod ten jest powtórzony w QuestionCrudControler, tutaj tez jest treningowo
 */
class BlamableSubscriber implements EventSubscriberInterface
{

	private Security $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	public function onBeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event): void
    {
        $question = $event->getEntityInstance();

	    //info ponieważ będzie to wywoływane przy każdym zapisie Encji w naszym systemie
	    //musimy sprawdzić, czy pracujemy na encji Question
		if (!$question instanceof Question) {
			return;
		}

		$user = $this->security->getUser();
		//info w systmie mamy jedną klase User. Jeżeli jesteśmy więc zalogowani to zmienna ta jest instancją klasy User :)
	    //taki myk sprawdzający czy jesteśmy zalogowani (chyba), komunikat się nie pojawi raczej nigdy :p
	    // pomaga to naszemu edytorowi dalej podpowiadać metody i właściwości
		if (!$user instanceof User) {
			throw new \LogicException('Currently looged user is not instance of User? :) ');
		}

		$question->setUpdatedBy($user);

    }

    public static function getSubscribedEvents(): array
    {
        return [
			//info Subscriber dalej jest tutaj, ale nic nie robi.
	        //wyłczyliśmy go bo kod wykonje się gdzie indziej
            //BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdatedEvent',
        ];
    }
}
