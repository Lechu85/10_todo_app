<?php

namespace App\Form; //question dlaczego było tutaj App\Form ?

use App\Entity\TaskCategory;
use App\Entity\User;
use Form\Type\DateTimeFromToType;
use Form\Type\TaskPriorytyType;
use Form\Type\TaskStatusType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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

	        ->add('dueDate', DateTimeFromToType::class, [
		        'label' => 'Termin realizacji ',
				'attr' => [
					'name' => 'dueDate'
				]
	        ])
            //todo usunac pole important
	        // ->add('important', ch)
            ->add('description', TextType::class, [
				'label' => 'Opis'
				//'data' => 'pierwszy tekst' //info domyślny tekst
            ])
            ->add('status', TaskStatusType::class,[
				'label' => 'Status zadania'
            ])

	        ->add('createdAt', DateTimeFromToType::class, [
		        'label' => 'Data utworzenia ',
		        'attr' => [
			        'name' => 'createdAt'
		        ]
	        ])
	        ->add('doneAt', DateTimeFromToType::class, [
		        'label' => 'Wykonane',
                'attr' => [
				    'name' => 'doneAt'
			    ]
	        ])

            ->add('prioryty', TaskPriorytyType::class)

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
			        'data-bs-target' => '#collapseAdvancedSearch',
	                'aria-expanded' => 'false',
	                'aria-controls' => 'collapseAdvancedSearch',
		        ]
	        ])
        ;

	    foreach ($builder->all() as $field) {

		    //if (in_array($field->getName(), ['save', 'hide'], true)) {
			//    continue;
		    //}

		    $fieldType = $field->getType()?->getInnerType();

		    if (
			    ($fieldType instanceof SubmitType) ||
			    ($fieldType instanceof ButtonType) ||
			    ($fieldType instanceof ResetType)
		    ) {
			    continue;
		    }

			$field->setRequired(false);

	    }
    }

	public function configureOptions(OptionsResolver $resolver): void
	{
		//question czy potrzeba tutaj encji dla wyszukiwarki zaawansowanej? co mi to daje?
	    /*$resolver->setDefaults([
	        'data_class' => Task::class,
	    ]);*/
	}
}
