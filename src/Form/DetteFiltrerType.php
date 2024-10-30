<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use App\enum\StatusDette;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetteFiltrerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status',ChoiceType::class,[
                
                'choices' => [
                    'All' => StatusDette::ALL->value,
                    'PAYE' => StatusDette::PAYE->value,
                    'IMPAYE' => StatusDette::IMPAYE->value,
                ],
                'label' => 'Status',
                // 'expanded' => true,
                // 'multiple' => true,
                'required' => false
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
            // Configure your form options here
        ]);
    }
}
