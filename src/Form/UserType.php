<?php

namespace App\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use App\Entity\Instrument;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de famille',
            ])
            ->add('pseudonym', TextType::class, [
                'label' => 'Pseudo',
                'required' => false,
            ])
            ->add('instrument', EntityType::class, [
                'class' => Instrument::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Instrument',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
            ])
            ->add('phone', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
            ])
            ->add('catchphrase', TextType::class, [
                'label' => 'Phrase d\'accroche',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
