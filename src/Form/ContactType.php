<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'attr' =>['class' =>'form-control'],
                'label' =>'Nom',
            ])
            ->add('adress',TextType::class,[
                'attr' =>['class' =>'form-control'],
                'label' =>'Adresse',
            ])
            ->add('zipcode',TextType::class,[
                'attr' =>['class' =>'form-control'],
                'label' =>'Code postal',
            ])
            ->add('city',TextType::class,[
                'attr' =>['class' =>'form-control'],
                'label' =>'Ville',
            ])
            ->add('phone',TextType::class,[
                'attr' =>['class' =>'form-control'],
                'label' =>'Téléphone',
            ])

            ->add('email',TextType::class,[
                'attr' =>['class' =>'form-control'],
                'label' =>'Email',
                'required' => false,
            ])

            ->add('text',TextareaType::class, [
                'attr' =>['class' =>'form-control'],
                'label' =>'Complément d\'information',
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
