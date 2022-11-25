<?php

namespace Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;


class DateTimeFromToType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder->add('From', DateType::class, [
			'widget' => 'single_text',
			'label' => $options['label'].' od',
			'attr' => [
				'name' => 'from',
			],
		])
		->add('To', DateType::class, [
			'widget' => 'single_text',
			'label' => $options['label'].' do',
			'attr' => [
				'name' => 'to',
			],

		]);
	}

}