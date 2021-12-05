<?php

namespace App\Form;

use App\Entity\Pacient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditPacientFormType extends AbstractType
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