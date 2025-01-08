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

#[Route('admin/')]
class AdminOrderController extends AbstractController
{

    #[Route('order_list', name: 'app_order_list_admin')]
    public function list(
        OrderRepository $orderRepo,
        Security        $security,
        Request         $request
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $orders = $orderRepo->findAll();
        $id = $orderRepo->searchByName($request->query->get('id', ''));
        if (empty($orders)) { 
            return $this->render('BackOffice/Order/emptyOrder.html.twig');
        }

        return $this->render('BackOffice/Order/order_list.html.twig', [
            'title' => 'Liste des commandes',
            'order' => $orders,
            'id' => $id,
        ]);
    }

    #[Route('order_show/{id}', name: 'app_order_show_admin')]
    public function showOrder(
        ?Orders  $order,
        Security $security,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('BackOffice/Order/order_show_admin.html.twig', [
            'title' => 'Fiche de la commande',
            'order' => $order,
        ]);
    }


    #[Route('update_order/{id}', name: 'app_update_order_admin')]
    public function update(
        Request            $request,
        ?Orders            $order,
        Security           $security,
        FormHandlerService $formHandler,
    )
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if (!$order) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $formResult = $formHandler->handleOrder($request, $order);

        if ($formResult) {
            return $this->redirectToRoute('app_order_list_admin');
        }
        return $this->render('BackOffice/Order/order_update.html.twig', [
            'title' => 'Mise à jour de la commande',
            'form' => $formResult->createView(),
        ]);
    }

    #[Route('kpi', name: 'app_kpi_admin')]
    public function KPI(
        Request            $request,
        ?Orders            $order,
        Security           $security,
        FormHandlerService $formHandler,
    )
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if (!$order) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $formResult = $formHandler->handleOrder($request, $order);

        if ($formResult) {
            return $this->redirectToRoute('app_order_list_admin');
        }
        return $this->render('BackOffice/Order/order_update.html.twig', [
            'title' => 'Mise à jour de la commande',
            'form' => $formResult->createView(),
        ]);
    }
}
