<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Inventaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id_restaurant', HiddenType::class, [
                'mapped' => false, // Ce champ n'est pas mappé à l'entité Inventaire
                'data' => $options['id_restaurant'], // Utiliser l'ID du restaurant passé en option
            ])
            ->add('code_vin', TextType::class, [
                'label' => 'Code Vin : ',

            ])
            ->add('qts', TextType::class, [
                'label' => 'Quantités : ',
            ])
            ->add('date_enregistrement', TextType::class, [
                'label' => 'Date Enregistrement : ',
                'data' => (new \DateTime())->format('Y-m-d H:i:s'),
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Inventaire::class,
            'id_restaurant' => null,
            'date_enregistrement' => null,
        ]);

        $resolver->setAllowedTypes('id_restaurant', ['int', 'null']);
    }
}
