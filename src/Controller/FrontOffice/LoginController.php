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
        //Récupérer les erreurs de connexion
        $error = $authenticationUtils->getLastAuthenticationError();
        //Récupérer le nom de l'utilisateur s'il existe
        $username = $authenticationUtils->getLastUsername();

        return $this->render('security/index.html.twig', [
            'title' => 'Connectez vous',
            'username' => $username,
            'error' => $error,
            'action' => '{{ path=\'app_index\' }}',
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
