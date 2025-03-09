<?php

namespace App\Controller\BackOffice;

use App\Entity\Users;
use App\Repository\AdminUsersRepository;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

// Classe pour gérer les opérations liées aux utilisateurs dans le back-office.
#[Route('admin/')]
class AdminUsersController extends AbstractController
{
    // Liste des utilisateurs.
    #[Route('user_list', name: 'app_user_list_admin')]
    public function list(
        UsersRepository    $usersRepo,
        Security $security,
        Request  $request
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        // Tri des utilisateurs par nom.
        $triLastName = $request->query->get('trilastname', 'asc');
        $triFirstName = $request->query->get('trifirstname', 'asc');
        $users = $usersRepo->searchByClients($request->query->get('lastname', ''), $triLastName, $triFirstName);

        return $this->render('BackOffice/User/admin_user_list.html.twig', [
            'title' => 'Liste des utilisateurs',
            'users' => $users,
            'trilastname' => $triLastName,
            'trifirstname' => $triFirstName,
            'lastname' => $request->query->get('lastname', ''),
        ]);
    }

    // Affiche la fiche d'un utilisateur.
    #[Route('show_user/{id}', name: 'app_user_show_admin')]
    public function show(
        ?Users   $users,
        Security $security,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        if (!$users) {
            // Redirige vers la liste si l'utilisateur n'existe pas.
            return $this->redirectToRoute('app_list_user_admin');
        }

        return $this->render('BackOffice/User/admin_user_show.html.twig', [
            'title' => 'Fiche d\'un utilisateur',
            'users' => $users,
        ]);
    }

    // Supprime un utilisateur.
    #[Route('delete_user/{id}', name: 'app_delete_user', methods: ['POST'])]
    public function delete(
        Request                $request,
        Users                  $users,
        EntityManagerInterface $entityManager,
        CartRepository         $cartRepo,
        CartProductRepository  $cartProduct
    ): Response
    {
        // Vérifie si l'utilisateur a le rôle ADMIN avant de continuer.
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        if (!$users) {
            // Redirige vers la liste si l'utilisateur n'existe pas.
            return $this->redirectToRoute('app_list_user_admin');
        }

        if ($this->isCsrfTokenValid('delete' . $users->getId(), $request->request->get('_token'))) {
            // Supprime les paniers et les produits associés à l'utilisateur.
            $cart = $cartRepo->findByUserId($users->getId());
            if ($cart) {
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
}