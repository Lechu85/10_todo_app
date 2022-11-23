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


	/*
	<option value="0" selected="" style="background-color: red; color: #fff;">Nowe</option>

	<option value="1" style="background-color: #d30203; color: #fff;">Odczytane</option>
	<option value="15" style="background-color: #0489cb; color: #fff;">Oczekiwanie na odpowiedź</option>
	<option value="13" style="background-color: #a417bd; color: #fff;">Oczekiwanie na dostawę</option>
	<option value="14" style="background-color: #6ab11a; color: #fff;">W przygotowaniu</option>

	<option value="16" style="background-color: orange; color: #fff; font-weight: bold">Finalizacja</option>

	<option value="2" style="background-color: green; color: #fff;">Zakończone</option>
	<option value="3" style="background-color: green; color: #fff;">Zakończone częściowo</option>

	<option value="4" style="background-color: #050D7A; color: #fff;">Anulowane</option>

	<option value="9" style="background-color: pink;">Wznowione</option>
	<option value="10" style="background-color: pink;">Odłożone</option>
	*/


	public function getParent(): string
	{
		return ChoiceType::class;
	}
}