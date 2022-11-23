<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\User;
use Form\Type\TaskStatusType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class TaskSearchType extends AbstractType
{
	private Security $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
	        ->add('title', SearchType::class)
	        ->add('dueDateFrom', DateType::class, [
		        'widget' => 'single_text',
		        'label' => 'Termin realizacji od fffff',
		        'format' => 'yyyy-MM-dd',
		        'attr' => ['data' => '01.05.2011', 'class' => 'klasaaaaa'],
	        ])
	        ->add('dueDateTo', DateType::class, [
		        'widget' => 'single_text',
		        'label' => 'Termin realizacji do',
		        'attr' => [
			        'name' => 'dueDateeeeeeeeeeTo'
		        ]
	        ])
            //todo usunac pole important
	        // ->add('important', ch)
            ->add('description', TextareaType::class, [
				'label' => 'Opis'
				//'data' => 'pierwszy tekst' //info domyślny tekst
            ])
            ->add('status', TaskStatusType::class,[
				'label' => 'Status zadania'

            ])

            ->add('createdAtFrom', DateType::class, [
	            'widget' => 'single_text',
	            'label' => 'Data utworzenia od:'
            ])
	        ->add('createdAtTo', DateType::class, [
		        'widget' => 'single_text',
		        'label' => 'Data utworzenia do:'
	        ])
            //->add('updatedAt', DateType::class, [
	        //    'widget' => 'single_text',
            //])

            ->add('prioryty', ChoiceType::class, [
	            'choices'  => [
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
            ])

            ->add('doneAtFrom', DateType::class, [
	            'widget' => 'single_text',
	            'label' => 'Wykonane od:'
            ])
	        ->add('doneAtTo', DateType::class, [
		        'widget' => 'single_text',
		        'label' => 'Wykonane do:'
	        ])

	        ->add('pinned', CheckboxType::class, [
		        'label' => 'Przypięte'
	        ])
            ->add('wontDo', CheckboxType::class, [
				'label' => 'Nie zrobię'
            ])
	        ->add('deleted', CheckboxType::class, [
		        'label' => 'W koszu'
	        ]);

		//only for ROLE_ADMIN
	        if ($this->security->isGranted('ROLE_ADMIN')) {

		        $builder
		        ->add('doneByUser', EntityType::class, [
					'label' => 'Wykonane przez użytkownika',
			        'class' => User::class,
			        'placeholder' => 'Wszyscy użytkownicy',
			        'choice_label' => 'email',
		        ])
				->add('user', EntityType::class, [

					'label' => 'Przypisane do użytkownika',
					'class' => User::class,
					'placeholder' => 'Wszyscy użytkownicy',
					'choice_label' => 'email',
				]);
			}

	        $builder->add('category', EntityType::class,[
				'label' => 'Kategoria',
				'required' => false,
		        'class' => TaskCategory::class,
		        'placeholder' => 'Kategoria',
		        'choice_label' => 'name'
	        ])
	        ->add('save', SubmitType::class,[
				'label' => 'Szukaj',
		        'attr' => ['class' => 'btn btn-primary']

	        ])
	        ->add('hide', ButtonType::class, [
				'label' => 'Ukryj wyszukiwarkę',
		        'attr' => [
					'class' => 'btn btn-danger',
			        'data-bs-toggle' => 'collapse',
			        'data-bs-target' => '#collapseExample',
	                'aria-expanded' => 'false',
	                'aria-controls' => 'collapseExample',
		        ]
	        ])
        ;

	    foreach ($builder->all() as $field) {

			/*
		    if (
			    ($field->getType() instanceof SubmitType::class) ||
			    ($field->getType() instanceof ButtonType::class)
		    ) {
			    continue;
		    }
			*/
		    if (
			    ($field->getName() === 'save') ||
			    ($field->getName() === 'hide')
		    ) {
			    continue;
		    }
			$field->setRequired(false);
	    }

    }

	public function configureOptions(OptionsResolver $resolver): void
	{
	    $resolver->setDefaults([
	        'data_class' => Task::class,
	        'required' => false,

	    ]);
	}
}
