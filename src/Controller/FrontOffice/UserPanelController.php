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

class UserPanelController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
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

    #[Route('/user/information/{id}', name: 'app_user_account')]
    public function userAccount(
        ?Users       $users,
        Request      $request,
        UsersService $usersService,
    ): Response
    {
        if (!$users) {
            return $this->redirectToRoute('app_index');
        }

        $result = $usersService->UsersForm($users, $request);
        if ($result['validate']) {
            return $this->redirectToRoute('app_user');
        }

        return $this->render('FrontOffice/user/updateAccount.html.twig', [
            'title' => 'Vos informations' . ' ' . $users->getFirstname() . ' ' . $users->getLastname(),
            'users' => $users,
            'form' => $result['form'],
        ]);
    }

}