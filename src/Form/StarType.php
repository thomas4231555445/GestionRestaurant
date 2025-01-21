<?php

namespace App\Form;

use App\Entity\Star;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_users', HiddenType::class, [
                'data' => $options['id_users'],
            ])
            ->add('pseudo', HiddenType::class, [
                'data' => $options['pseudo'],
            ])
            ->add('star', TextType::class, [
                'label' => 'Votre Note : ',
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Star::class,
            'id_users' => null,
            'pseudo' => null,
        ]);
    }
}
