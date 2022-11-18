<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
	        //->add('task')
	        ->add('dueDate', DateType::class, [
		        'widget' => 'single_text',
		        'label' => 'Termin realizacji'
	        ])
            ->add('important')
            ->add('description')
            ->add('status')
            ->add('createdAt', DateType::class, [
	            'widget' => 'single_text',
	            'label' => 'Data utworzenia'
            ])
            //->add('updatedAt', DateType::class, [
	        //    'widget' => 'single_text',
            //])
            ->add('deleted')
            ->add('prioryty')
            ->add('pinned')
            //->add('doneAt')
            //->add('doneByUser')
            ->add('remind')
            ->add('wontDo')
	        ->add('user', EntityType::class, [
		        // looks for choices from this entity
		        'class' => User::class,
		        'placeholder' => 'Wszyscy użytkownicy',
		        'choice_label' => 'email',
	        ])
	        ->add('category', EntityType::class,[
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
	        'role' => 'ROLE_USER'
        ]);
    }
}
