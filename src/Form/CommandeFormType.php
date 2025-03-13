<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Delivery;
use App\Entity\Orders;
use App\Entity\Payment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

// Classe pour gérer les formulaires pour les commandes.
class CommandeFormType extends AbstractType
{
    // Methode pour construire le formulaire.
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $adressesUtilisateur = $options['adressesUtilisateur'];
        $builder
            ->add('delivery', EntityType::class, [
                'class' => Delivery::class,
                'choice_label' => 'title',

            ])
            ->add('payment', EntityType::class, [
                'class' => Payment::class,

                'choice_label' => 'title',
            ])
            ->add('billed', EntityType::class, [
                'class' => Adress::class,
                'choices' => $adressesUtilisateur,
                'choice_label' => 'street',
                'required' => true,
                'placeholder' => 'Sélectionnez une adresse de facturation',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une adresse de facturation',
                    ]),
                ],
            ])
            ->add('delivered', EntityType::class, [
                'class' => Adress::class,
                'choices' => $adressesUtilisateur,
                'choice_label' => 'street',
                'required' => true,
                'placeholder' => 'Sélectionnez une adresse de livraison',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une adresse de livraison',
                    ]),
                ],
            ]);
    }

    // Methode pour configurer les options du formulaire.
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
            'adressesUtilisateur' => [],
        ]);
    }
}
