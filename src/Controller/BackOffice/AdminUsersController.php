<?php

namespace App\Controller\BackOffice;

use App\Entity\Users;
use App\Repository\AdminUsersRepository;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('admin/')]
class AdminUsersController extends AbstractController
{

    #[Route('user_list', name: 'app_user_list_admin')]
    public function list(
        AdminUsersRepository $usersRepo,
        Security             $security,
        Request              $request
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $triLastName = $request->query->get('trilastname', 'asc');
        $triFirstName = $request->query->get('trifirstname', 'asc');
        $users = $usersRepo->searchByName($request->query->get('lastname', ''), $triLastName, $triFirstName);

        return $this->render('BackOffice/User/admin_user_list.html.twig', [
            'title' => 'Liste des utilisateurs',
            'users' => $users,
            'trilastname' => $triLastName,
            'trifirstname' => $triFirstName,
            'lastname' => $request->query->get('lastname', ''),
        ]);
    }

    #[Route('show_user/{id}', name: 'app_user_show_admin')]
    public function show(
        ?Users   $users,
        Security $security,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if (!$users) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('BackOffice/User/admin_user_show.html.twig', [
            'title' => 'Fiche d\'un utilisateur',
            'users' => $users,
        ]);
    }

    #[Route('delete_user/{id}', name: 'app_delete_user', methods: ['POST'])]
    public function delete(
        Request                $request,
        Users                  $users,
        EntityManagerInterface $entityManager,
        CartRepository         $cartRepo,
        CartProductRepository  $cartProduct
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $users->getId(), $request->request->get('_token'))) {
            $cart = $cartRepo->findByUserId($users->getId());
            if ($cart) {
                $products = $cartProduct->findByCartId($cart[0]->getId());
                if ($products) {
                    foreach ($products as $product) {
                        $entityManager->remove($product);
                    }
                }
                $entityManager->remove($cart[0]);

            }
            $entityManager->remove($users);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_list_admin', [], Response::HTTP_SEE_OTHER);
    }
}
