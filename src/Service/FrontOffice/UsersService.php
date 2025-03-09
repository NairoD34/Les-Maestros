<?php

namespace App\Service\FrontOffice;

use App\Entity\Users;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

// Classe pour gérer les opérations liées aux formulaires de gestion des utilisateurs dans le front-office.
class UsersService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly FormFactoryInterface   $formFactoryInterface,
    )
    {

    }

    // Methode pour traiter le formulaire de gestion des utilisateurs.
    public function UsersForm(
        Users   $users,
        Request $request,
    )
    {
        $form = $this->formFactoryInterface->create(UserFormType::class, $users);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->em->persist($users);
            $this->em->flush();
            return [
                'validate' => true,
                'form' => $form,
            ];
        }

        return [
            'validate' => false,
            'form' => $form,
        ];
    }
}