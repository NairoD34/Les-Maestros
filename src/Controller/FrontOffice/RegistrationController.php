<?php

namespace App\Controller\FrontOffice;

use App\Service\FrontOffice\PasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request         $request,
        PasswordService $passwordService,
    ): Response
    {
        $result = $passwordService->CreatePasswordForm($request);
        if ($result['validate']) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('FrontOffice/registration/register.html.twig', [
            'registrationForm' => $result['form']->createView(),
        ]);
    }
}
