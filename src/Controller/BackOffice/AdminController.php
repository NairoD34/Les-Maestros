<?php

namespace App\Controller\BackOffice;

use App\Entity\Admin;
use App\Entity\Users;
use App\Repository\AdminRepository;
use App\Repository\UsersRepository;
use App\Service\BackOffice\FormHandlerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('admin/')]
class AdminController extends AbstractController
{
    #[Route('list_admin', name: 'app_list_admin')]
    public function list(UsersRepository $adminRepo, Request $request): Response
    {
        $trinom = $request->query->get('trinom', 'asc');
        $triprenom = $request->query->get('triprenom', 'asc');
        $admin = $adminRepo->searchByName($request->query->get('lastname', ''), $trinom, $triprenom);

        return $this->render('BackOffice/Admin/list.html.twig', [
            'title' => 'Liste des administrateurs',
            'administrateur' => $admin,
            'trinom' => $trinom,
            'triprenom' => $triprenom,
            'nom' => $request->query->get('nom', ''),
        ]);
    }

    #[Route('show/{id}', name: 'app_show_admin')]
    public function show(?Admin $admin): Response
    {
        if (!$admin) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('BackOffice/Admin/show.html.twig', [
            'title' => 'Fiche d\'un administrateur',
            'admin' => $admin,
        ]);
    }

    #[Route('new', name: 'app_new_admin')]
    public function new(
        Request                     $request,
        Security                    $security,
        UserPasswordHasherInterface $adminPasswordHasher,
        FormHandlerService          $formHandler
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $admin = new Users();
        $formResult = $formHandler->handleAdmin(false, $request, $admin, $adminPasswordHasher);

        if ($formResult['validate']) {
            return $this->redirectToRoute('app_list_admin');
        }


        return $this->render('BackOffice/Admin/new.html.twig', [
            'title' => 'Création d\'un nouvel administrateur',
            'form' => $formResult['form']->createView(),
        ]);
    }

    #[Route('update/{id}', name: 'app_update_admin')]
    public function update(
        Request                     $request,
        ?Users                      $admin,
        Security                    $security,
        FormHandlerService          $formHandler,
        UserPasswordHasherInterface $adminPasswordHasher,
    )
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        if (!$admin) {
            return $this->redirectToRoute('app_list_admin');
        }

        $formResult = $formHandler->handleAdmin(false, $request, $admin, $adminPasswordHasher);

        if ($formResult) {
            return $this->redirectToRoute('app_list_admin');
        }

        return $this->render('BackOffice/Admin/new.html.twig', [
            'title' => 'Mise à jour d\'un administrateur',
            'form' => $formResult,
        ]);
    }

    #[Route('delete/{id}', name: 'app_delete_admin', methods: ['POST'])]
    public function delete(
        Request                $request,
        Admin                  $admin,
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $admin->getId(), $request->request->get('_token'))) {
            $entityManager->remove($admin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_list_admin', [], Response::HTTP_SEE_OTHER);
    }
}
