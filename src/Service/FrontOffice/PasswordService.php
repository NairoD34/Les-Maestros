<?php

namespace App\Service\FrontOffice;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PasswordService
{
    public function __construct(
        private readonly FormFactoryInterface        $formFactory,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly EntityManagerInterface      $entityManager,
    ) {
        
    }

    public function CreatePasswordForm($request, Users $user, ValidatorInterface $validatorInterface)
    {
        $form = $this->formFactory->create(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $validate = false;
        $errors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            // do anything else you need here, like send an email
            $validate = true;
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $errors = $validatorInterface->validate($user);
        }

        return [
            'validate' => $validate,
            'form' => $form,
            'errors' => $errors,
        ];
    }

    public function ResetPasswordForm(
        Request $request,
        Users $users,
        )
    {        
        $form = $this->formFactory->create(ResetPasswordFormType::class, $users);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $users->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $users,
                    $form->get('Password')->getData()
                )
            );
            $this->entityManager->persist($users);
            $this->entityManager->flush();

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