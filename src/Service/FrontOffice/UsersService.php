<?php

namespace App\Service\FrontOffice;

use App\Entity\Users;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UsersService
{
    public function __construct(
        private EntityManagerInterface $em,
        private FormFactoryInterface $formFactoryInterface,
    ) {
        
    }

    public function UsersForm(
        Users $users,
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