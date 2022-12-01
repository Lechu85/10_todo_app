<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskStatusType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'Status zadania',
			'choices' => [
				'Nowe' => 1,
				'Odczytane' => 2,
				'Oczekiwanie na odpowiedź' => 3,
				'Oczekiwanie na dostawę' => 4,
				'W przygotowaniu' => 5,
				'Finalizacja' => 6,
				'Zakończone' => 7,
				'Zakończone częściowo' => 8,
				'Anulowane' => 9,
				'Wznowione' => 10,
				'Odłożone' => 11,
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
