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
        private readonly EntityManagerInterface $em,
        private readonly FormFactoryInterface   $formFactoryInterface,
    )
    {

    }

    public function UsersForm(
        Users   $users,
        Request $request,
    )
    {
        $form = $this->formFactoryInterface->create(UserFormType::class, $users);
        $validate = false;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($users);
            $this->em->flush();
            $validate = true;
        }

        return [
            'validate' => $validate,
            'form' => $form,
        ];
    }
}