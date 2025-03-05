<?php

namespace App\Controller\FrontOffice;

use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Service\FrontOffice\PasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Classe pour gérer la gestion des mots de passe oublies.
class ResetPasswordController extends AbstractController
{

    //Route pour vérifier l'existence de l'utilisateur.
    #[Route('/reset-password', name: 'app_reset_password_check_email')]
    public function checkEmail(Request $request, UsersRepository $usersRepo): Response
    {
        //Recherche de l'utilisateur à partir de l'adresse e-mail fournie dans le formulaire.
        $email = $request->request->get('email');

        if ($email) {
            //Recherche de l'utilisateur à partir de l'adresse e-mail fournie dans le formulaire.
            $user = $usersRepo->findOneBy(['email' => $email]);

            if ($user) {
                //Redirige vers la page de reset du mot de passe.
                return $this->redirectToRoute('app_reset_password_form', ['id' => $user->getId()]);
            }

            //Affiche un message d'erreur si l'utilisateur n'existe pas.
            $this->addFlash('error', 'Adresse e-mail invalide.');
        }

        return $this->render('auth/reset_password/check_email.html.twig');
    }

    //Route pour le formulaire de reset du mot de passe.
    #[Route('/reset-password/{id}', name: 'app_reset_password_form')]
    public function resetPassword(
        Users           $users,
        Request         $request,
        PasswordService $passwordService,
    ): response
    {
        //Recherche de l'utilisateur à partir de l'identifiant fourni dans l'URL.
        $users = $usersRepo->find($id);
        if (!$users) {
            //Affiche un message d'erreur si l'utilisateur n'existe pas.
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('app_reset_password_check_email');
        }

        //Gestion du formulaire de reset du mot de passe.
        $result = $passwordService->ResetPasswordForm($request, $users);
        if ($result['validate']) {
            //Affiche un message de succès et redirige vers la page de connexion.
            $this->addFlash("success", "mot de passe réinitialisé avec succès");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/reset_password/reset-password.html.twig', [
            'title' => 'Changement de mot de passe',
            'form' => $result['form'],
        ]);
    }
}