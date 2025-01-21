<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('banniere', ChoiceType::class, [
                'choices' => [
                    'Banniere1' => 'img/bannvins1.png',
                    'Banniere2' => 'img/bannvins2.png',
                    'Banniere3' => 'img/bannvins3.png',
                ],
                'expanded' => false,
                'multiple' => false,

            ])
            ->add('avatar', ChoiceType::class, [
                'choices' => [
                    'Avatar1' => 'img/D1.png',
                    'Avatar2' => 'img/D2.png',
                    'Avatar3' => 'img/D3.png',
                ],
                'expanded' => false,
                'multiple' => false,

            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo'
        ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Que faites vous ?',
                'choices' => [
                    'Restaurateur' => 'ROLE_USER',
                    'Viticulteur' => 'ROLE_MAIN',
                ],
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,

            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least 6 characters long',
                        'max' => 4096,
                    ]),
                ]
            ])
            ;



            $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();


                $selectedRole = $form->get('roles')->getData();


                $data->setRoles([$selectedRole]);

                $event->setData($data);
            });

            }


public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}