<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
	        ->add('task', null, [
				'label' => 'Task name',
		        'help' => 'Set task name',
	        ])
	        ->add('dueDate', DateType::class, [
		        // renders it as a single text box
		        'widget' => 'single_text',
	        ])
	        ->add('user', EntityType::class, [
		        // looks for choices from this entity
		        'class' => User::class,
		        'choice_label' => 'email',
	        ])
	        ->add('important')
	        ->add('agreeTerms', CheckboxType::class, ['mapped' => false])
	        ->add('save', SubmitType::class)
	        ->setMethod('GET')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
		//domyslna encja dla tego formularza
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
