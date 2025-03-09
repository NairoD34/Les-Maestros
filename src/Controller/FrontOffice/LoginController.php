<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

//Classe pour gérer l'accès des utilisateurs à la page de connexion.
class LoginController extends AbstractController
{
    //Route pour la page de connexion.
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->isGranted('ROLE_ADMIN')) {
            // Redirige vers la page d'accueil si l'utilisateur est connecté.
            return $this->redirectToRoute('app_admin_dashboard');
        }
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirige vers la page d'accueil si l'utilisateur est connecté.
            return $this->redirectToRoute('app_index');
        }

        // Gestion des erreurs de connexion.
        $error = $authenticationUtils->getLastAuthenticationError();
        $error_msg = "";
        if ($error) {
            // Affiche un message d'erreur si l'utilisateur n'a pas les droits.
            $error_msg = "Identifiant ou mot de passe incorrect";
        }

        // Recherche du nom d'utilisateur en cours.
        $username = $authenticationUtils->getLastUsername();

        return $this->render('auth/security/index.html.twig', [
            'title' => 'Connectez vous',
            'username' => $username,
            'error' => $error_msg,
        ]);
    }

    //Route pour la page de logout.
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    //Route pour la redirection apres la connexion.
    #[Route('/redirect', name: 'app_redirect_after_login')]
    public function redirectAfterLogin(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            // Redirige vers la page de connexion si l'utilisateur n'existe pas.
            return $this->redirectToRoute('app_login');
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->redirectToRoute('app_index');
    }

}
