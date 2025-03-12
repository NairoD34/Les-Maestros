<?php

namespace App\Controller\BackOffice;

use App\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\BackOffice\FormHandlerService;

// Contrôleur pour gérer les opérations liées aux commandes dans le back-office.
#[Route('admin/')]
class AdminOrderController extends AbstractController
{
    // Affiche la liste des commandes.
    #[Route('order_list', name: 'app_order_list_admin')]
    public function list(
        OrderRepository $orderRepo,
        Security        $security,
        Request         $request
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers l'accueil si l'utilisateur n'a pas le rôle ADMIN.
            return $this->redirectToRoute('app_index');
        }

        // Recherche des commandes par ID.
        $orders = $orderRepo->findAll();
        $id = $orderRepo->searchByName($request->query->get('id', ''));
        if (empty($orders)) {
            //Affiche la page spécifique aux commandes vides si aucune n'existe.
            return $this->render('BackOffice/Order/empty_order.html.twig');
        }

        return $this->render('BackOffice/Order/order_list.html.twig', [
            'title' => 'Liste des commandes',
            'order' => $orders,
            'id' => $id,
        ]);
    }

    // Affiche les détails d'une commande spécifique.
    #[Route('order_show/{id}', name: 'app_order_show_admin')]
    public function showOrder(
        ?Orders  $order,
        Security $security,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            //Redirige vers l'accueil si l'utilisateur n'a pas le rôle ADMIN.
            return $this->redirectToRoute('app_index');
        }

        if (!$order) {
            //Redirige vers la liste des commandes si la commande n'existe pas.
            return $this->redirectToRoute('app_order_list_admin');
        }

        return $this->render('BackOffice/Order/order_show_admin.html.twig', [
            'title' => 'Fiche de la commande',
            'order' => $order,
        ]);
    }

    // Mise à jour d'une commande.
    #[Route('update_order/{id}', name: 'app_update_order_admin')]
    public function update(
        Request            $request,
        ?Orders            $order,
        Security           $security,
        FormHandlerService $formHandler,
    )
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            //Redirige vers l'accueil si l'utilisateur n'a pas le rôle ADMIN.
            return $this->redirectToRoute('app_index');
        }

        if (!$order) {
            //Redirige vers la liste des commandes si la commande n'existe pas.
            return $this->redirectToRoute('app_order_list_admin');
        }

        $formResult = $formHandler->handleOrder($request, $order);

        if ($formResult['validate']) {
            return $this->redirectToRoute('app_order_list_admin');
        }

        return $this->render('BackOffice/Order/order_update.html.twig', [
            'title' => 'Mise à jour de la commande',
            'form' => $formResult["form"]->createView(),
        ]);
    }
}
