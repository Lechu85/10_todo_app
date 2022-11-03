<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

		//jak dolożyć validacje php jak nie ma encji?
        $builder
            ->add('name', TextType::class, ['label' => 'Autor'])
	        ->add('email', EmailType::class, ['label' => 'Twój email'])
	        ->add('content', TextareaType::class, ['label' => 'Treść emaila'])
	        ->add('save', SubmitType::class, ['label' => 'Wyślij formularz'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
