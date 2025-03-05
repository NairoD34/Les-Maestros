<?php

namespace App\Controller\FrontOffice;

use App\Entity\Users;
use App\Service\FrontOffice\PasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request         $request,
        PasswordService $passwordService,
        ValidatorInterface $validatorInterface,
    ): Response
    {
        $user = new Users();
        $result = $passwordService->CreatePasswordForm($request, $user, $validatorInterface);
        if ($result['validate']) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('FrontOffice/registration/register.html.twig', [
            'form' => $result['form']->createView(),
            'errors' => $result['errors'],
        ]);
    }
}
