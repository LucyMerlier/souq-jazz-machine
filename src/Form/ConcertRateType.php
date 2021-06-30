<?php

namespace App\Form;

use App\Entity\ConcertRate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcertRateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', TextType::class, [
                'label' => 'Catégorie de personnes (adultes, enfants...)',
                'required' => false,
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'EUR',
                'label' => 'Prix de l\'entrée',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConcertRate::class,
        ]);
    }
}
