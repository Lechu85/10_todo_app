<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\User;
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
		        'label' => 'Termin realizacji od',
		        'format' => 'yyyy-MM-dd'
	        ])
	        ->add('dueDateTo', DateType::class, [
		        'widget' => 'single_text',
		        'label' => 'Termin realizacji do'
	        ])
            //todo usunac pole important
	        // ->add('important', ch)
            ->add('description', TextareaType::class, [
				'label' => 'Opis'
				//'data' => 'pierwszy tekst' //info domyślny tekst
            ])
            ->add('status', ChoiceType::class,[
				'choices' => [
					'Nowe' => 0,
					'Odczytane' => 1,

				]
            ])


        /*
	    <option value="0" selected="" style="background-color: red; color: #fff;"></option>

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
