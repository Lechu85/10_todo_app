<?php
namespace Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskPriorytyType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'choices'  => [
				'Wszystkie' => 0,
				'Krytyczne' => 4,
				'Ważne' => 3,
				'Zwykłe' => 2,
				'Mało ważne' => 1,
			],
			'choice_attr' => [
				'Krytyczne' => ['data-color' => 'Red'],
				'Ważne' => ['data-color' => 'Yellow'],
				'Zwykłe' => ['data-color' => 'Green'],
				'Mało ważne' => ['data-color' => 'Green'],
			],
		]);
	}

	public function getParent(): string
	{
		return ChoiceType::class;
	}
}