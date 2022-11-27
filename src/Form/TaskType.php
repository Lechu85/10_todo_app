<?php

namespace App\Form;

use App\Config\TaskStatus;
use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\User;
use Form\Type\TaskPriorytyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

		if (!empty($options['data'])) {
			$btnText = 'Zapisz zmiany';
		} else {
			$btnText = 'Dodaj zadanie';
		}

        $builder
	        ->add('title', null, [
				'label' => 'Tytuł zadania',
		        'help' => 'Skrócona treśc zadania',
	        ])
	        ->add('description', TextareaType::class,[
				'label' => 'Opis zadania',
		        'help' => 'Rozwinięty opis zadania',
	        ])
	        /*->add('status', TaskStatusType::class,[
				'placeholder' => ' : Wybierz status : ',
		        'label' => 'Status zadania',
		        'attr' => [
				    'class' => 'w-50'
			    ],
	        ])*/
	        ->add('status', EnumType::class,[
				'class' => TaskStatus::class,
		        'placeholder' => ' : Wybierz status : ',
		        'label' => 'Status zadania',
		        'attr' => [
			        'class' => 'w-50'
		        ],
	        ])
	        ->add('dueDate', DateType::class, [
				'label' => 'Termin realizacji',
				'required' => false,
		        'widget' => 'single_text',
		        'attr' => [
			        'class' => 'w-50'
		        ],
	        ])
	        ->add('user', EntityType::class, [
				'label' => 'Przypisz do użytkownika',
		        'placeholder' => ' : Wybierz użytkownika : ',
		        'class' => User::class,
		        'choice_label' => 'name',
		        'attr' => [
					'class' => 'w-50'
		        ],
	        ])
	        ->add('category', EntityType::class,[
				'label' => 'Kategoria',
				'placeholder' => ' : Wybierz kategorie : ',
				'class' => TaskCategory::class,
		        'choice_label' => 'name',
		        'attr' => [
					'class' => 'w-50'
		        ],
	        ])

	        ->add('prioryty', TaskPriorytyType::class, [
				'data' => 0,
		        'attr' => [
			        'class' => 'w-50'
		        ],
	        ])
	        ->add('pinned', CheckboxType::class, [
		        'required' => false,
		        'label' => 'Przypięte',
	        ])
	        ->add('wontDo', CheckboxType::class, [
				'required' => false,
		        'label' => 'Nie zrobię',
	        ])

	        ->add('save', SubmitType::class,[
				'label' => $btnText
	        ])
	        ->add('back', ButtonType::class, [
		        'label' => 'Powrót do listy zadań',
		        'attr' => [
			        'id' => 'btn_back',
			        'class' => 'btn-link',
		        ]
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
