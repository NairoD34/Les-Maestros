<?php

namespace App\Controller\FrontOffice;

use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Service\FrontOffice\PasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{

    #[Route('/reset-password', name: 'app_reset_password_check_email')]
    public function checkEmail(Request $request, UsersRepository $usersRepo): Response
    {
        $email = $request->request->get('email');

        if ($email) {
            $user = $usersRepo->findOneBy(['email' => $email]);
            
            if ($user) {
                return $this->redirectToRoute('app_reset_password_form', ['id' => $user->getId()]);
            } else {
                $this->addFlash('error', 'Adresse e-mail invalide.');
            }
        }

        return $this->render('reset_password/check_email.html.twig');
    }

    #[Route('/reset-password/{id}', name: 'app_reset_password_form')]
    public function resetPassword(
        Users $users, 
        Request $request, 
        PasswordService $passwordService,
        ): response
    {
        $result = $passwordService->ResetPasswordForm($request, $users);
        if ($result['validate']) {
            $this->addFlash("success", "mot de passe réinitialisé avec succès");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset-password.html.twig', [
            'title' => 'Changement de mot de passe',
            'form' => $result['form'],
        ]);
    }
}