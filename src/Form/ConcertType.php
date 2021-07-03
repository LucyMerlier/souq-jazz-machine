<?php

namespace App\Form;

use App\Entity\Concert;
use App\Form\ConcertRateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class, [
                'label' => 'Date et heure',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'help' => 'Ce champ supporte le markdown ;)',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Concert::class,
        ]);
    }
}
