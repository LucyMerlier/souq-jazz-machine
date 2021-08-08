<?php

namespace App\Form;

use App\DataClass\ApplyOffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplyOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Marcus'],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Miller'],
            ])
            ->add('emailAddress', EmailType::class, [
                'label' => 'E-mail',
                'attr' => ['placeholder' => 'marcus@miller.bass'],
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Téléphone (facultatif)',
                'attr' => ['placeholder' => '06 07 08 09 10'],
                'required' => false,
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => ['placeholder' => 'J\'aimerais faire partie de la bande!'],
            ])
            ->add('honeypot', TextType::class, [
                'mapped' => false,
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'honeypot',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApplyOffer::class,
        ]);
    }
}
