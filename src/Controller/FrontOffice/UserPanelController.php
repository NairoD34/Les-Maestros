<?php

namespace App\Controller\FrontOffice;

use App\Entity\Adress;
use App\Entity\Users;
use App\Service\FrontOffice\UsersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Classe pour gérer les opérations liées au panel utilisateur.
#[Route('/user')]
class UserPanelController extends AbstractController
{
    //Affiche la page d'accueil du panel utilisateur.
    #[Route('/', name: 'app_user')]
    public function indexAccount(
        Security $security,
        ?Adress  $adress
    ): Response
    {
        $user = $security->getUser();
        return $this->render('FrontOffice/user/index.html.twig', [
            'title' => 'Vos informations',
            'users' => $user,
            'adresse' => $adress,
        ]);
    }

    //Affiche la page de modification des informations du compte utilisateur.
    #[Route('/user/information/{id}', name: 'app_user_account')]
    public function userAccount(
        ?Users       $users,
        Request      $request,
        UsersService $usersService,
    ): Response
    {
        //Recuperation de l'utilisateur connecté
        if (!$users) {
            //Redirection vers la page d'accueil si l'utilisateur n'est pas connecté
            $this->addFlash("error", 'Vous n\'avez pas les droits pour effectuer cette action');
            return $this->redirectToRoute('app_index');
        }

        //Affichage du formulaire pour la modification des informations du compte
        $result = $usersService->UsersForm($users, $request);
        if ($result['validate']) {
            //Affichage du message de succès et redirige vers la page des informations
            $this->addFlash("success", 'Vos informations ont été modifiée');
            return $this->redirectToRoute('app_user');
        }

        return $this->render('FrontOffice/user/updateAccount.html.twig', [
            'title' => 'Vos informations' . ' ' . $users->getFirstname() . ' ' . $users->getLastname(),
            'users' => $users,
            'form' => $result['form'],
        ]);
    }

}