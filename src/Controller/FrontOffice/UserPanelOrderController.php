<?php

namespace App\Controller\FrontOffice;

use App\Entity\Orders;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPanelOrderController extends AbstractController
{
    #[Route('/user/order_list', name: 'app_commande_list')]
    public function list(
        OrderRepository $orderRepo,
        Security        $security,
        Request         $request
    ): Response
    {

        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        $user = $this->getUser();
        $orders = $orderRepo->findBy(['users' => $user->getId()]);

        if (empty($orders)) {
            return $this->render('FrontOffice/user/emptyCommande.html.twig');
        }
        return $this->render('FrontOffice/user/commande_list.html.twig', [
            'title' => 'Liste des commandes',
            'order' => $orders,
            'id' => $request->query->get('id', ''),
        ]);
    }

    #[Route('/user/order_show/{id}', name: 'app_commande_show')]
    public function show(
        ?Orders  $orders,
        Security $security,
    ): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }
        return $this->render('FrontOffice/user/commande_show.html.twig', [
            'title' => 'Fiche de la commande',
            'order' => $orders,
        ]);
    }
}
