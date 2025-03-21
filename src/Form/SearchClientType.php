<?php

namespace App\Form;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\DTO\ClientSearchDto;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;



class SearchClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('telephon', TextType::class, [
            'required' => false,
            'attr' => [
                'placeholder' => 'Telephone',
                // 'pattern' => '^([77|78|76])[0-9]{7}$',
                // 'class' => 'text-danger' // Ajouté ici
            ],
            'constraints' => [
                new Regex([
                    'pattern'=> '/^([77|78|76])([0-9]{7})$/'
                ])
            ]
        ]) 
        ->add('surname', TextType::class, [
            'required' => false,
            'attr' => [
                'placeholder' => 'Surname',
                // 'pattern' => '^[a-zA-Z]+$'
            ]
        ])
        ->add('Search',SubmitType::class,[
            'attr' => [
                'class' => 'bg-blue-600 text-white px-4 py-2 rounded'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'data_class' => ClientSearchDto::class,
        ]);
    }
}
