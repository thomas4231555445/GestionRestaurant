<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Vins;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VinsModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('couleur', ChoiceType::class, [
                'label' => 'Couleur : ',
                'choices' => [
                    'Rouges' => 'Rouges',
                    'Blancs' => 'Blancs',
                    'Roses' => 'Roses',
                    'Petillants' => 'Petillants',
                    'Petillants Roses' => 'Petillants Roses',
                ],
                'attr' => ['class' => 'code-vin-source'],
            ])
            ->add('type', TextType::class, [
                'label' => 'Type : ',
            ])
            ->add('appellation', TextType::class, [
                'label' => 'Appellation : ',
                'attr' => ['class' => 'code-vin-source'],
            ])
            ->add('nom_producteur', TextType::class, [
                'label' => 'Nom Producteur : ',
                'attr' => ['class' => 'code-vin-source'],
            ])
            ->add('domaine', TextType::class, [
                'label' => 'Domaine : ',
            ])
            ->add('nom_vin', TextType::class, [
                'attr' => ['class' => 'code-vin-source'],
            ])
            ->add('cl', TextType::class, [
                'label' => 'Contenant : ',
                'attr' => ['class' => 'code-vin-source'],
            ])
            ->add('millesime', TextType::class, [
                'label' => 'Millesime : ',
                'attr' => ['class' => 'code-vin-source'],
            ])
            ->add('description', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('id_restaurant', HiddenType::class);
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
