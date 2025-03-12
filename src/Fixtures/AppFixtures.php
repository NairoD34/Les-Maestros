<?php

// src/DataFixtures/AppFixtures.php
namespace App\Fixtures;

use App\Entity\Users;
use App\Entity\State;
use App\Entity\TaxRate;
use App\Entity\Payment;
use App\Entity\Delivery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new Users();
        $admin->setLastname('Admin'); 
        $admin->setFirstname('Admin');
        $admin->setEmail('admin@mail.fr');
        $admin->setPassword(password_hash('lesMaestros34@', PASSWORD_BCRYPT));
        $admin->setRoles(['ROLE_ADMIN']);
        
        $manager->persist($admin);

        $state1 = new State();
        $state1->setTitle('En attente de paiement');
        $manager->persist($state1);

        $state2 = new State();
        $state2->setTitle('Payé');
        $manager->persist($state2);

        $state3 = new State();
        $state3->setTitle('En cours de livraison');
        $manager->persist($state3);
        
        $state4 = new State();
        $state4->setTitle('Livré');
        $manager->persist($state4);


        $taxRate1 = new TaxRate();
        $taxRate1->setTaxRate(5.5);
        $manager->persist($taxRate1);

        $taxRate2 = new TaxRate();
        $taxRate2->setTaxRate(20);
        $manager->persist($taxRate2);

        $payment1 = new Payment();
        $payment1->setTitle('chèque');
        $manager->persist($payment1);

        $payment2 = new Payment();
        $payment2->setTitle('espèce');
        $manager->persist($payment2);

        $delivery1 = new Delivery();
        $delivery1->setTitle('livraison en magasin');
        $manager->persist($delivery1);

        $delivery2 = new Delivery();
        $delivery2->setTitle('livraison à domicile');
        $manager->persist($delivery2);

        $manager->flush();

    }
}