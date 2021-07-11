<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('images', FileType::class, [
                'label' => 'Images à ajouter',
                'mapped' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '2M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/gif',
                                'image/png',
                                'image/webp'
                            ],
                        ])
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
