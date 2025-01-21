<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Vins;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'mapped' => false,
                'data' => $options['data']['description'] ?? '', // Pré-remplir le champ description
            ])
            ->add('id_restaurant', HiddenType::class, [
                'data' => $options['id_restaurant'], // Utiliser l'ID du restaurant passé en option
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'id_restaurant' => null,
            'data' => [],
        ]);

        $resolver->setAllowedTypes('id_restaurant', ['int', 'null']);
        $resolver->setAllowedTypes('data', ['array']);
    }
}

