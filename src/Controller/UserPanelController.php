<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Users;
use App\Service\FrontOffice\UsersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPanelController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function indexAccount(
        Security $security, 
        ?Adresse $adresse
        ): Response
    {
        $user = $security->getUser();
        return $this->render('user/index.html.twig', [
            'title' => 'Vos informations',
            'users' => $user,
            'adresse' => $adresse,
        ]);
    }

    #[Route('/user/information/{id}', name: 'app_user_account')]
    public function userAccount(
        ?Users $users, 
        Request $request, 
        UsersService $usersService,
        ): Response
    {
        if ($users === null) {
            return $this->redirectToRoute('app_index');
        }
        
        $result = $usersService->UsersForm($users, $request);
        if ($result['validate']) {
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/updateAccount.html.twig', [
            'title' => 'Vos informations' . ' ' . $users->getPrenom(),
            'users' => $users,
            'form' => $result['form'],
        ]);
    }
   
}