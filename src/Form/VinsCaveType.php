<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\VinsCave;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class VinsCaveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id_cave', HiddenType::class, [
                'mapped' => false,
                'data' => $options['id_cave'],
                ])
            ->add('code_vin',TextType::class, [
                'label' => 'Ajouter une Réference',
                'attr' => [
                    'placeholder' => 'CODE VIN'
                ]
            ])
            ->add('ligne', HiddenType::class, [
                'mapped' => false,
                'data' => $options['ligne'],
            ])
            ->add('colonne', HiddenType::class, [
                'mapped' => false,
                'data' => $options['colonne'],
            ])
            ->add('id_restaurant', HiddenType::class, [
                'mapped' => false,
                'data' => $options['id_restaurant'],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VinsCave::class,
            'id_cave' => null,
            'ligne' => null,
            'colonne' => null,
            'id_restaurant' => null,
        ]);
        $resolver->setAllowedTypes('id_cave', ['int', 'null']);
        $resolver->setAllowedTypes('ligne', ['int', 'null']);
        $resolver->setAllowedTypes('colonne', ['int', 'null']);
        $resolver->setAllowedTypes('id_restaurant', ['int', 'null']);
    }
}
