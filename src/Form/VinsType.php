<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\BaseVins;
use App\Entity\Fournisseurs;
use App\Entity\Vins;

use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VinsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('couleur', ChoiceType::class, [
                'label' => 'Couleur *',
                'choices' => [
                    'Rouges' => 'Rouges',
                    'Blancs' => 'Blancs',
                    'Roses' => 'Roses',
                    'Petillants' => 'Petillants',
                    'Petillants Roses' => 'Petillants Roses',
                ],

                    'attr' => ['class' => 'code-vin-source']
                ])

            ->add('type', TextType::class, [
                'label' => 'Type *',
            ])
            ->add('appellation', TextType::class, [
                'label' => 'Appellation *',
                'attr' => ['class' => 'code-vin-source']
            ])
            ->add('nom_producteur', TextType::class, [
                'label' => 'Nom Producteur *',
                'attr' => ['class' => 'code-vin-source']
            ])
            ->add('domaine', TextType::class, [
                'label' => 'Domaine *',
            ])
            ->add('nom_vin', TextType::class, [
                'attr' => ['class' => 'code-vin-source']
            ])
            ->add('cl', TextType::class, [
                'label' => 'Cl *',
                'attr' => ['class' => 'code-vin-source']
            ])
            ->add('millesime', TextType::class, [
                'label' => 'Millesime *',
                'attr' => ['class' => 'code-vin-source']
            ])
            ->add('code_vin', TextType::class, [

            ])

            ->add('prix_achat_ht')
            ->add('prix_achat_ttc')
            ->add('prix_vente_ht')
            ->add('prix_vente_ttc')
            ->add('id_restaurant', HiddenType::class, [
                'label' => ' ',
                'data' => $options['id_restaurant'], // Utiliser l'ID du restaurant passé en option
            ])
            ->add('stock', TextType::class, [
                'label' => 'Stock *',
            ])
        ->add('description', TextareaType::class, [
            'mapped' => false,
            ])
            ->add('fournisseur', EntityType::class, [
                'label' => 'Fournisseurs *',
                'class' => Fournisseurs::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('f')
                        ->where('f.id_restaurant = :id_restaurant')
                        ->setParameter('id_restaurant', $options['id_restaurant']);
                },
                'choice_label' => 'nom', // Le champ à afficher dans le select
            ])
            ->add('contact', TextType::class, [
                    'mapped' => false,
                ]
            )
            ->add('actus', ChoiceType::class, [
                'label' => 'Référence présente dans le fil d\'actualité ?',
                'mapped' => false,
                'choices' => [
                    'Oui' => '1',
                    'Non' => '0',
                ],
                'expanded' => true, // Affiche les options sous forme de cases à cocher
                'multiple' => false, // Permet de sélectionner une seule option
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vins::class,
            'id_restaurant' => null,
        ]);

        $resolver->setAllowedTypes('id_restaurant', ['int', 'null']);
    }
}
