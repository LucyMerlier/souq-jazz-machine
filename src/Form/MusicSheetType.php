<?php

namespace App\Form;

use App\Entity\Instrument;
use App\Entity\MusicSheet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class MusicSheetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', VichFileType::class, [
                'label' => 'Fichier au format PDF',
                'allow_delete' => false,
                'download_uri' => false,
            ])
            ->add('instrument', EntityType::class, [
                'class' => Instrument::class,
                'choice_label' => 'name',
                'required' => false,
                'expanded' => false,
            ])
            ->add('specification', TextType::class, [
                'label' => 'Spécification (alto, conducteur...)',
                'required' => false,
                'attr' => [
                    'list' => 'specifications',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MusicSheet::class,
        ]);
    }
}
