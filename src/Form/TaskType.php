<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
		        'help' => 'Podaj nazwe zadania',
	        ])
	        ->add('description', TextareaType::class,[
				'label' => 'Opis zadania',
		        'help' => 'Podaj dłuższy opis zadania'
	        ])
	        ->add('dueDate', DateType::class, [
		        // renders it as a single text box
		        'widget' => 'single_text',
	        ])
	        ->add('user', EntityType::class, [
		        'placeholder' => ' : Wszyscy użytkownicy : ',
		        'class' => User::class,
		        'choice_label' => 'email',
	        ])
	        ->add('category', EntityType::class,[
		        'placeholder' => ' : Wszystkie kategorie : ',
				'class' => TaskCategory::class,
		        'choice_label' => 'name'
	        ])
	        ->add('important')
	        ->add('agreeTerms', CheckboxType::class, ['mapped' => false])
	        ->add('save', SubmitType::class,[
				'label' => 'Dodaj nowe zadanie'
	        ])
	        ->add('back', ButtonType::class, [
				'label' => 'Powrót',
		        'attr' => ['id' => 'btn_back']
	        ])

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
