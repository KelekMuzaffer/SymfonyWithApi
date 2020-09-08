<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginUserType extends AbstractType
{
    // Création d'un formulaire pour la page loginUser.html.twig
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => ['maxlength' => 50, 'class' => 'big-text'],
                'constraints' => [
                    new NotBlank([
                        'message' => "Votre email n'est pas enregistrez dans notre base.",
                    ]),
                ]
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe!']),
                    new Length(['min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au moins 8 caractères.',
                        'max' => 20
                    ]),
                ],
                'attr' => ['autocomplete' => 'off']
            ])
            ->add('Login', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
