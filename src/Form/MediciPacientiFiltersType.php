<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MediciPacientiFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'attr' => [
                    'placeholder' => 'Caută după nume, prenume sau email'
                ],
                'mapped' => false,
                'required' => false,
            ])
        ;
    }
}