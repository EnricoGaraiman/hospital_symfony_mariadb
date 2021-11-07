<?php

namespace App\Form;

use App\Entity\Medic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedicProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenumeMedic', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Prenume',
                'attr' => [
                    'placeholder'=>'Tastează prenumele'
                ],
            ])
            ->add('numeMedic', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Nume',
                'attr' => [
                    'placeholder'=>'Tastează numele'
                ],
            ])
            ->add('email', EmailType::class, [
                'row_attr' => ['class' => 'input'],
                'attr' => ['readonly'=>true],
                'label' => 'Email',
            ])
            ->add('specializare', TextType::class, [
                'row_attr' => ['class' => 'input'],
                'label' => 'Specializare',
                'attr' => [
                    'placeholder'=>'Tastează specializarea'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medic::class,
        ]);
    }
}