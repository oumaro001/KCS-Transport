<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class FilteredByRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('car', EntityType::class, [
            'class' => Car::class,
            'choice_label' => 'register',
            'label' => 'Véhicule',
            'placeholder' => 'Aucun véhicule',
            'required' => false,
        ])
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
