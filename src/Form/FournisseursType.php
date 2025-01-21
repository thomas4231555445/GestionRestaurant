<?php

namespace App\Form;

use App\Entity\Fournisseurs;
use App\Entity\Vins;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FournisseursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom',
                ]
            ])
            ->add('siren', TextType::class, [
                'label' => 'Siren',
                'attr' => [
                    'placeholder' => 'Siren',
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Telephone',
                'attr' => [
                    'placeholder' => 'Telephone',
                ]
            ])
            ->add('mail', TextType::class, [
                'label' => 'Mail',
                'attr' => [
                    'placeholder' => 'Mail',
                ]
            ])
            ->add('pays', TextType::class, [
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Pays',
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Adresse',
                ]
            ])
            ->add('code_postale', TextType::class, [
                'label' => 'Code Postal',
                'attr' => [
                    'placeholder' => 'Code Postal',
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Ville',
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'attr' => [
                    'placeholder' => 'Prenom',
                ]
            ])
            ->add('nom_famille', TextType::class, [
                'label' => 'Nom Famille',
                'attr' => [
                    'placeholder' => 'Nom Famille',
                ]
            ])
            ->add('telephoneperso', TextType::class, [
                'label' => 'Telephone',
                'attr' => [
                    'placeholder' => 'Telephone',
                ]
            ])
            ->add('mailperso', TextType::class, [
                'label' => 'Mail Perso',
                'attr' => [
                    'placeholder' => 'Mail ',
                ]
            ])
            ->add('id_restaurant', HiddenType::class, [
                'data' => $options['id_restaurant'], // Utiliser l'ID du restaurant passé en option
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fournisseurs::class,
            'id_restaurant' => null,
        ]);

        $resolver->setAllowedTypes('id_restaurant', ['int', 'null']);
    }
}
