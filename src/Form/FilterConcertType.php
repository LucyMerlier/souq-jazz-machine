<?php

namespace App\Form;

use App\DataClass\FilterConcert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sort', ChoiceType::class, [
                'choices' => FilterConcert::CHOICES,
                'required' => false,
                'label' => false,
                'placeholder' => 'Toutes les dates',
                'attr' => [
                    'aria-label' => 'trier par',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FilterConcert::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
