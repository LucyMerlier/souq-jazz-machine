<?php

namespace App\Form;

use App\Entity\Instrument;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo de profil (facultatif)',
                'allow_delete' => true,
                'download_uri' => false,
                'required' => false,
                'help' => 'Seules les images carrées de moins de 2M sont autorisées'
            ])
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
