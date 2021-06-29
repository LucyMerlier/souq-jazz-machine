<?php

namespace App\Form;

use App\Entity\Instrument;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255,
                    ]),
                ],
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255,
                    ]),
                ],
                'label' => 'Nom de famille',
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255,
                    ]),
                    new Email()
                ],
                'label' => 'Adresse email',
            ])
            ->add('instrument', EntityType::class, [
                'class' => Instrument::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'Instrument',
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
