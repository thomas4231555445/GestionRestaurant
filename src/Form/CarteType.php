<?php

namespace App\Form;

use App\Entity\Carte;
use App\Entity\Vins;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Selection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('id_restaurant', HiddenType::class, [
                'mapped' => false, // Ce champ n'est pas mappé à l'entité Inventaire
                'data' => $options['id_restaurant'], // Utiliser l'ID du restaurant passé en option
            ])
            ->add('background', ColorType::class, [
                'label' => 'Couleur de fond',
                'help' => 'Choisissez une couleur de fond pour la carte'
            ])
            ->add('logo')
            ->add('nom_etablissement')
            ->add('selection')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carte::class,
            'id_restaurant' => null,
        ]);
    }
}
