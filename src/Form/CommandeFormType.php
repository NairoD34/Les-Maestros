<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Order;
use App\Entity\Sales;
use App\Entity\Delivery;
use App\Entity\Payment;
use App\Entity\Cart;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $adressesUtilisateur = $options['adressesUtilisateur'];
        $builder

            ->add('Sales', EntityType::class, [
                'class' => Sales::class,
                'choice_label' => 'title',

            ])
            ->add('Payment', EntityType::class, [
                'class' => Payment::class,

                'choice_label' => 'title',
            ])
            ->add('billed', EntityType::class, [
                'class' => Adress::class,
                'choices' => $adressesUtilisateur,
                'choice_label' => 'street',


            ])
            ->add('delivered', EntityType::class, [
                'class' => Adress::class,
                'choices' => $adressesUtilisateur,
                'choice_label' => 'street',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'adressesUtilisateur' => [],
        ]);
    }
}
