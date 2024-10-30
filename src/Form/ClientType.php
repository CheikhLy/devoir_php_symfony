<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('surname', TextType::class, [
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    // 'placeholder' => 'Nom',
                    // 'pattern' => '^[a-zA-Z]+$'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez votre nom',
                    ]),
                    new NotBlank([
                        'message' => 'Le champ ne peut pas être vide',
                    ]),
                ]
            ])
            ->add('telephon', TextType::class, [
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Telephone',
                    // 'pattern' => '^([77|78|76])[0-9]{7}$',
                    // 'class' => 'text-danger' // Ajouté ici
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez votre numéro de telephone',
                    ]),
                    new NotBlank([
                        'message' => 'Le champ ne peut pas être vide',
                    ]),
                    new Regex([
                        'pattern' => '/^(77|78|76)[0-9]{7}$/',
                        'message' => 'Le numéro de téléphone doit commencer par 77, 78 ou 76 et contenir 7 chiffres supplémentaires.',
                    ])
                    
                ]
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Adresse',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez votre adresse',
                    ]),
                    new NotBlank([
                        'message' => 'Le champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('addUser', CheckboxType::class, [
                'label' => 'Ajouter un compte ?',
                'required' => false,
                'data' => false,
                'mapped' => false,

                'attr' => [
                    'class' => 'form-check-input',
                ]
            ])
            ->add('users', UserType::class, [
                'label'=> false,
                'attr' => [
                    'class' => 'hidden',
                ],
                ])

            ->add('Save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
