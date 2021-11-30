<?php

namespace App\Form;

use App\Entity\Pacient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PacientProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenumePacient', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Prenume'
            ])
            ->add('numePacient', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Nume'
            ])
            ->add('cnp', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'attr' => ['readonly'=>true],
                'label' => 'CNP'
            ])
            ->add('email', EmailType::class, [
                'row_attr' => ['class' => 'input'],
                'attr' => ['readonly'=>true],
                'label' => 'Email'
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
                'label' => 'Adresă',
                'required' => false
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