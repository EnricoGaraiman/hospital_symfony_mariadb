<?php

namespace App\Form;

use App\Entity\Pacient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenumePacient', TextType::class, [
                'row_attr'=>['class'=>'input'],
                'attr'=>[
                    'placeholder'=>'Tastează prenumele'
                ],
                'label'=>'Prenume'
            ])
            ->add('numePacient', TextType::class, [
                'row_attr'=>['class'=>'input'],
                'attr'=>[
                    'placeholder'=>'Tastează numele'
                ],
                'label'=>'Nume'
            ])
            ->add('cnp', TextType::class, [
                'row_attr'=>['class'=>'input'],
                'attr'=>[
                    'placeholder'=>'Tastează CNP-ul'
                ],
                'label'=>'CNP'
            ])
            ->add('email', EmailType::class, [
                'row_attr'=>['class'=>'input'],
                'attr'=>[
                    'placeholder'=>'Tastează emailul'
                ],
                'label'=>'Email'
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'row_attr'=>['class'=>'form-check'],
                'attr'=>['class'=>'form-check-input'],
                'mapped' => false,
                'label'=> 'Acceptă termenii și condițiile',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Trebuie să fii de acord cu termenii și condițiile.',
                    ]),
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pacient::class,
        ]);
    }
}
