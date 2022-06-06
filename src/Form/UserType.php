<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['class' => 'form-control'], 'label' => 'Email'])
            ->add('lastname', TextType::class, ['attr' => ['class' => 'form-control'], 'label' => 'Nom',])
            ->add('firstname', TextType::class, ['attr' => ['class' => 'form-control'], 'label' => 'Prenom'])
            ->add('adress', TextType::class, ['attr' => ['class' => 'form-control'], 'label' => 'Adresse'])
            ->add('zipcode', TextType::class, ['attr' => ['class' => 'form-control'], 'label' => 'Code postal'])
            ->add('city', TextType::class, ['attr' => ['class' => 'form-control'], 'label' => 'Ville'])
            ->add('fonction', TextType::class, ['attr' => ['class' => 'form-control'], 'label' => 'Fonction'])
            ->add('car', EntityType::class, [
                'class' => Car::class,
                'choice_label' => 'register',
                'label' => 'Véhicule',
                'placeholder' => 'Aucun véhicule',
                'required' => false,

            ])
            ->add('phone', TextType::class, ['attr' => ['class' => 'form-control'], 'label' => 'Phone'])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label' => 'Photo'
            ])

            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'choices'  => [
                    'Salariées' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    
                ], 'attr' => ['class' => 'form-control'], 'label' => 'Status'
            ]);
        // roles field data transformer 
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
