<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Cave;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id_restaurant', HiddenType::class, [
            'mapped' => false, // Ce champ n'est pas mappé à l'entité Inventaire
                'data' => $options['id_restaurant'], // Utiliser l'ID du restaurant passé en option
            ])
            ->add('num_cave', TextType::class, [
                'label' => 'Nom Cave',
                'required' => false,
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cave::class,
            'id_restaurant' => null,
        ]);
        $resolver->setAllowedTypes('id_restaurant', ['int', 'null']);
    }

}
