<?php

namespace App\Form;

use App\Entity\Partner;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email professionnelle (facultatif)',
                'required' => false,
            ])
            ->add('phone', TelType::class, [
                'label' => 'Numéro de téléphone profesisonnel (facultatif)',
                'required' => false,
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie (ancien membre, salle de concert...)',
                'choices' => Partner::CATEGORIES,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Description (facultatif)',
                'config_name' => 'custom_config',
                'required' => false,
                'help' => 'Veuillez ne pas renseigner d\'informations personnelles',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
        ]);
    }
}
