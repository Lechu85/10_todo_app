<?php

namespace Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;


class DateTimeFromToType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder->add('From', DateTimeType::class, [
			'widget' => 'single_text',
			'label' => $options['label'].' od',
			'attr' => [
				'name' => 'from',
			],
		])
		->add('To', DateTimeType::class, [
			'widget' => 'single_text',
			'label' => $options['label'].' do',
			'attr' => [
				'name' => 'to',
			],

		]);
	}

}