<?php

namespace App\Form;

use App\Entity\Consultatie;
use App\Entity\Medic;
use App\Entity\Medicament;
use App\Entity\Pacient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConsultatieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pacient', EntityType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Pacient',
                'class' => Pacient::class,
                'placeholder' => '',
            ])
            ->add('medicament', EntityType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Medicament',
                'class' => Medicament::class,
                'required' => false,
                'placeholder' => '',
            ])
            ->add('dozaMedicament', NumberType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Doză medicament [ml]',
                'attr' => [
                    'placeholder'=>'Setează doza medicamentului [ml]'
                ],
                'invalid_message' => '',
                'empty_data' => 0,
                'required' => false
            ])
            ->add('diagnostic', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Diagnostic',
                'attr' => [
                    'placeholder'=>'Tastează diagnosticul'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultatie::class,
        ]);
    }
}