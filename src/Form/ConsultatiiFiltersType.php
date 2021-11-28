<?php

namespace App\Form;

use App\Entity\Medic;
use App\Entity\Medicament;
use App\Entity\Pacient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class ConsultatiiFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('medic', EntityType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Medic',
                'class' => Medic::class,
                'placeholder' => '',
                'mapped' => false,
                'required' => false,
            ])
            ->add('pacient', EntityType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Pacient',
                'class' => Pacient::class,
                'placeholder' => '',
                'mapped' => false,
                'required' => false,
            ])
            ->add('medicament', EntityType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Medicament',
                'class' => Medicament::class,
                'placeholder' => '',
                'mapped' => false,
                'required' => false,
            ])
            ->add('data1', DateType::class, [
                'input' => 'string',
                'row_attr' => ['class' => 'input'],
                'widget' => 'single_text',
                'label' => 'De la',
                'mapped' => false,
                'required' => false,
            ])
            ->add('data2', DateType::class, [
                'input' => 'string',
                'row_attr' => ['class' => 'input'],
                'widget' => 'single_text',
                'label' => 'Până la',
                'mapped' => false,
                'required' => false,
            ])
        ;
    }
}