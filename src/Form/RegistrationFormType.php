<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
	        ->add('name', TextType::class, [
		        'label' => 'Twoje imię',
		        'required' => 'true',
		        'constraints' => [
			        new NotBlank([
				        'message' => 'Podaj swoje imię',
			        ]),
			        new Length([
				        'min' => 6,
				        'minMessage' => 'Minimalna ilość znaków: {{ limit }} ',
				        // max length allowed by Symfony for security reasons
				        'max' => 4096,
			        ]),
		        ],
	        ])
            ->add('email', null, [
				'label' => 'Adres email',
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
	            'type' => PasswordType::class,
	            'invalid_message' => 'Hasła muszą być jednakowe',
				'required' => true,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj hasło',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Minimalna ilość znaków: {{ limit }} ',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
	            'first_options'  => array('label' => 'Podaj hasło'),
	            'second_options' => array('label' => 'Powtórz hasło'),
            ])
	        ->add('agreeTerms', CheckboxType::class, [
				'label' => 'Akceptuje <A href="/regulamin" target="_blank">regulamin</a>',
		        'label_html' => true,
		        'mapped' => false,
		        'constraints' => [
			        new IsTrue([
				        'message' => 'You should agree to our terms.',
			        ]),
		        ],
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
