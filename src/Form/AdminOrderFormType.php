<?php

namespace App\Form;


use App\Entity\Orders;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Classe pour gérer les opérations liées aux commandes dans le back-office.
class AdminOrderFormType extends AbstractType
{
    //Methode pour construire le formulaire.
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('State', EntityType::class, [
                'class' => State::class,
                'choice_label' => 'title',
            ]);

    }

    //Methode pour configurer les options du formulaire.
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
