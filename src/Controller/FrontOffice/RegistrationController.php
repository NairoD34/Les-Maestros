<?php

namespace App\Controller\FrontOffice;

use App\Entity\Users;
use App\Service\FrontOffice\PasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//Classe pour gérer l'inscription des utilisateurs.
class RegistrationController extends AbstractController
{
    //Route pour l'inscription des utilisateurs.
    #[Route('/register', name: 'app_register')]
    public function register(
        Request         $request,
        PasswordService $passwordService,
        ValidatorInterface $validatorInterface,
    ): Response
    {
        //Redirige vers la page d'accueil si l'utilisateur est connecté.
        if ($this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        $user = new Users();
        $result = $passwordService->CreatePasswordForm($request, $user, $validatorInterface);
        if ($result['validate']) {
            //Ajoute un message de succès et redirige vers la page de connexion.
            $this->addFlash("success", "inscription réaliser avec succès");
            return $this->redirectToRoute('app_login');
        }
        return $this->render('FrontOffice/registration/register.html.twig', [
            'form' => $result['form']->createView(),
            'errors' => $result['errors'],
        ]);
    }
}
