<?php

namespace Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskStatusType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'choices' => [
				'Nowe' => 0,
				'Odczytane' => 1,
				'Oczekiwanie na odpowiedź' => 2,
				'Oczekiwanie na dostawę' => 3,
				'W przygotowaniu' => 4,
				'Finalizacja' => 5,
				'Zakończone' => 6,
				'Zakończone częściowo' => 7,
				'Anulowane' => 8,
				'Wznowione' => 9,
				'Odłożone' => 10,
			],
			'choice_attr' => [
				'Krytyczne' => ['data-color' => 'Red'],
				'Nowe' => ['data-color' => 'Red'],
				'Odczytane' => ['data-color' => 'Red'],
				'Oczekiwanie na odpowiedź' => ['data-color' => 'Red'],
				'Oczekiwanie na dostawę' => ['data-color' => 'Red'],
				'W przygotowaniu' => ['data-color' => 'Red'],
				'Finalizacja' => ['data-color' => 'Red'],
				'Zakończone' => ['data-color' => 'Green'],
				'Zakończone częściowo' => ['data-color' => 'Green'],
				'Anulowane' => ['data-color' => 'Red'],
				'Wznowione' => ['data-color' => 'Red'],
				'Odłożone' => ['data-color' => 'Red'],
			],
		]);
	}

	public function getParent(): string
	{
		return ChoiceType::class;
	}
}