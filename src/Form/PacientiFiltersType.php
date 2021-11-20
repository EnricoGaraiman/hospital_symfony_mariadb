<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PacientiFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'attr' => [
                    'placeholder' => 'Caută după nume, prenume, email sau cnp'
                ],
                'mapped' => false,
                'required' => false,
                'label' => 'Pacient',
            ])
            ->add('asigurare', ChoiceType::class, [
                'choices' => [
                    'Nu' => 0,
                    'Da' => 1,
                ],
                'mapped' => false,
                'required' => false,
                'row_attr' => ['class' => 'input'],
                'label' => 'Asigurare',
                'label_attr' => ['class' => 'select2-label']
            ])
        ;
    }
}
