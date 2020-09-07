<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationUserType extends AbstractType
{
    // Création d'un formulaire pour la page registrationUser.html.twig
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => ['maxlength' => 50, 'class' => 'big-text'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrez un email',
                    ]),
                ]
            ])
            ->add('password', RepeatedType::class, [
                'required' => true,
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe *', 'attr' => ['class' => 'big-text', 'minlength' =>  8, 'maxlength' => 20, 'placeholder' => '']],
                'second_options' => ['label' => 'Répétez le mot de passe *', 'attr' => ['class' => 'big-text', 'minlength' => 8, 'maxlength' => 20, 'placeholder' => '']],
                'invalid_message' => 'Le mot de passe ne correspond pas',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe valide']),
                    new Length(['min' => 8,
                                'minMessage' => 'Votre mot de passe doit contenir au moins 8 caractères',
                                'max' => 20
                    ]),
                ],
                'attr' => ['autocomplete' => 'off']
            ])
            ->add('Inscription', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
