<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                "required" => true,
                'attr' => ['maxlength' => 50, 'class' => 'big-text'],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez indiquez le titre de l'article."
                    ]),
                ]
            ])
            ->add('content', TextType::class, [
                "required" => true,
                "attr" => ['maxlength' => 150, 'class' => 'big-text'],
                "constraints" => [
                    new NotBlank([
                        'message' => 'Veuillez ecrire un contenue décrivant votre article.'
                    ]),
                ]
            ])
            ->add('price', NumberType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Vous avez oubliez d'inscrire un prix!"
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => "Le prix minimum est de 1 euros.",
                        "max" => 4
                    ]),
                ],
            ])
            ->add("Ajoutez", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
