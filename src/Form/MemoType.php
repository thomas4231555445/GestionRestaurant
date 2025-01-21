<?php

namespace App\Form;

use App\Entity\Memo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_users', HiddenType::class, [
                'mapped' => false,
                'data' => $options['id_users'],
            ])
            ->add('texte', TextareaType::class, [
                'label' => 'Texte',
                'attr' => [
                    'rows' => 4, 'cols' => 50, 'placeholder' => 'Mémos'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Memo::class,
            'id_users' => null,
        ]);
    }
}
