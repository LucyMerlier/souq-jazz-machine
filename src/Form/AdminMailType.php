<?php

namespace App\Form;

use App\DataClass\AdminMail;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recipients', ChoiceType::class, [
                'label' => 'Destinataires',
                'choices' => AdminMail::RECIPIENTS_CATEGORIES,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet',
            ])
            ->add('message', CKEditorType::class, [
                'label' => 'Votre message',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdminMail::class,
        ]);
    }
}
