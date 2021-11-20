<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MediciFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'attr' => [
                    'placeholder' => 'Caută după nume, prenume, email sau specializare'
                ],
                'mapped' => false,
                'required' => false,
                'label' => 'Medic',
            ])
            ->add('administrator', ChoiceType::class, [
                'choices' => [
                    'Medic' => 0,
                    'Administrator' => 1,
                ],
                'mapped' => false,
                'required' => false,
                'row_attr' => ['class' => 'input'],
                'label' => 'Administrator / Medic',
                'label_attr' => ['class' => 'select2-label']
            ])
        ;
    }
}