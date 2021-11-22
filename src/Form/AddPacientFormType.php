<?php

namespace App\Form;

use App\Entity\Pacient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddPacientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenumePacient', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Prenume',
                'attr' => [
                    'placeholder'=>'Tastează prenumele'
                ],
            ])
            ->add('numePacient', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Nume',
                'attr' => [
                    'placeholder'=>'Tastează numele'
                ],
            ])
            ->add('email', EmailType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Email',
                'attr' => [
                    'placeholder'=>'Tastează emailul'
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder'=>'Tastează parola'
                ],
                'label'=>'Parola',
                'row_attr'=>['class'=>'input'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Parola ta trebuie să aibe minim {{ limit }} caractere',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('cnp', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'CNP',
                'attr' => [
                    'placeholder'=>'Tastează CNP'
                ],
            ])
            ->add('asigurare', ChoiceType::class, [
                'choices' => [
                    'Nu' => 0,
                    'Da' => 1,
                ],
                'row_attr' => ['class' => 'input'],
                'label' => 'Asigurare',
                'label_attr' => ['class' => 'select2-label']
            ])
            ->add('adresa', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Adresa',
                'required' => false,
                'attr' => [
                    'placeholder'=>'Tastează adresa'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pacient::class,
        ]);
    }
}