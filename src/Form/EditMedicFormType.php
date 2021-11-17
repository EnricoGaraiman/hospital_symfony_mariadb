<?php

namespace App\Form;

use App\Entity\Medic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditMedicFormType extends AbstractType
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
            ->add('administrator', ChoiceType::class, [
                'choices' => [
                    'Nu' => 0,
                    'Da' => 1,
                ],
                'mapped' => false,
                'row_attr' => ['class' => 'input'],
                'label' => 'Permite acces de administrator',
                'label_attr' => ['class' => 'select2-label']
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