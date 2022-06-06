<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class FilteredByRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('register',TextType::class,['attr' => ['class' => 'form-control','placeholder'=>'BN-987-FU'], 'label' => 'Immatriculation','required' => false,],)
            ->add('Rechercher',SubmitType::class,['attr' =>['class' => 'btn btn-success mt-3']])
           ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
