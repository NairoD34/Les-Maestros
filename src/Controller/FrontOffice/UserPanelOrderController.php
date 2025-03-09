<?php

namespace App\Controller\FrontOffice;

use App\Entity\Orders;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Classe pour gérer les opérations liées aux commandes dans le panel utilisateur.
class UserPanelOrderController extends AbstractController
{
    //Methode pour afficher la liste des commandes de l'utilisateur connecté.
    #[Route('/user/order_list', name: 'app_commande_list')]
    public function list(
        OrderRepository $orderRepo,
        Security        $security,
        Request         $request
    ): Response
    {

        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            //Redirection vers la page d'accueil si l'utilisateur n'est pas connecté
            return $this->redirectToRoute('app_index');
        }

        //Recuperation de l'utilisateur connecté
        $user = $this->getUser();
        //Appel du repository pour obtenir la liste des commandes
        $orders = $orderRepo->findBy(['users' => $user->getId()]);

        if (empty($orders)) {
            //Redirection vers la page des commandes si aucune commande n'existe
            return $this->render('FrontOffice/user/emptyCommande.html.twig');
        }
        return $this->render('FrontOffice/user/commande_list.html.twig', [
            'title' => 'Liste des commandes',
            'order' => $orders,
            'id' => $request->query->get('id', ''),
        ]);
    }

    //Methode pour afficher les détails d'une commande.
    #[Route('/user/order_show/{id}', name: 'app_commande_show')]
    public function show(
        ?Orders  $orders,
        Security $security,
    ): Response
    {
        //Verification si l'utilisateur est connecté
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            //Redirection vers la page d'accueil si l'utilisateur n'est pas connecté
            return $this->redirectToRoute('app_index');
        }

        if (!$orders) {
            //Redirection vers la page des commandes si la commande n'existe pas
            return $this->redirectToRoute('app_commande_list');
        }

        if($orders->getUsersID() !== $security->getUser()->getId()){
            //Redirection vers la page des commandes si la commande n'existe pas
            return $this->redirectToRoute('app_commande_list');
        }
        return $this->render('FrontOffice/user/commande_show.html.twig', [
            'title' => 'Fiche de la commande',
            'order' => $orders,
        ]);
    }
}
