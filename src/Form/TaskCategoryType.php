<?php

namespace App\Form;

use App\Entity\TaskCategory;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

	    if (!empty($options['data'])) {
		    $btnText = 'Zapisz zmiany';
	    } else {
		    $btnText = 'Dodaj grupę';
	    }

        $builder
            ->add('name', null, [
				'label' => 'Nazwa grupy',
            ])
            ->add('description', TextareaType::class, [
				'label' => 'Opis grupy'
            ])
            ->add('icon', TextType::class, [
				'label' => 'Ikonka',
				'attr' => [
					'class' => 'w-50'
				],
            ])

            ->add('pin', null, [
				'label' => 'Przypięte',
            ])
            ->add('archive')
            ->add('color', null, [
	            'attr' => [
		            'class' => 'w-50'
	            ],
            ])
            ->add('type', null, [
	            'attr' => [
		            'class' => 'w-50'
	            ],
            ])
            ->add('hidden', null, [
				'label' => 'Ukryj w kalendarzu',
            ])
	        ->add('taskCount', IntegerType::class, [
		        'label' => 'Ilość przypisanych zadań',
				'required' => false,
		        'attr' => [
			        'class' => 'w-25',
			        'readonly' => true,
		        ],
	        ])
	        ->add('save', SubmitType::class, [
				'label' => $btnText,
	        ])
	        ->add('back', ButtonType::class, [
		        'label' => 'Powrót do listy zadań',
		        'attr' => [
					'id' => 'btn_back',
			        'class' => 'btn-link',
		        ]
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskCategory::class,
        ]);
    }
}
