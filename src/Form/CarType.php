<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand',TextType::class,['attr' => ['class' => 'form-control'], 'label' => 'Marque'])
            ->add('model',TextType::class,['attr' => ['class' => 'form-control'], 'label' => 'Modéle'])
            ->add('register',TextType::class,['attr' => ['class' => 'form-control'], 'label' => 'Immatriculation'])
            ->add('places',IntegerType::class,['attr' => ['class' => 'form-control'], 'label' => 'Places'])
            ->add('ramp', CheckboxType::class, [
                'label'    => 'Rampe',
                'required' => false,
            ])
            ->add('imageFile',VichImageType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control'], 'label' => 'Photo']) 
           ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
