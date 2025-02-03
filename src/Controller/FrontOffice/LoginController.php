<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $error_msg = "";
        if ($error) {
            $error_msg = "Identifiant ou mot de passe incorrect";
        }
        $username = $authenticationUtils->getLastUsername();

        return $this->render('auth/security/index.html.twig', [
            'title' => 'Connectez vous',
            'username' => $username,
            'error' => $error_msg,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/redirect', name: 'app_redirect_after_login')]
    public function redirectAfterLogin(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->redirectToRoute('app_index');
    }

}
