<?php

namespace App\Controller\FrontOffice;

use App\Entity\Adress;
use App\Service\FrontOffice\AdressService;
use App\Service\FrontOffice\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Classe pour gérer les opérations liées aux commandes.
class OrderController extends AbstractController
{
    //Route pour afficher la page de nouveau message.
    #[Route('/order', name: 'app_commande')]
    public function NewOrder(
        Security    $security,
        CartService $cartService,
        Request     $request,
    ): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        // Récupération du total du montant des produit dans le panier
        $total = $cartService->CalculCart()['total'];

        // Validation du formulaire
        if ($cartService->FormOrdersValidation($request)) {
            // Enregistrement de la commande
            $this->addFlash('success', 'Votre commande a bien été validée.');
            return $this->redirectToRoute('app_order_list');
        }


        return $this->render('FrontOffice/order/index.html.twig', [
            'controller_name' => 'OrderController',
            'form' => $cartService->GetUserData()['form'],
            'totalttc' => $total,
        ]);
    }

    //Affichage Formulaire pour l'entité Adresse
    private function formOrderAdress(
        ?Adress       $adress,
        Request       $request,
        AdressService $adressService,
        Security      $security
    )
    {
        $message = '';
        $users = $security->getUser();
        if ($adressService->SaveAdressForm($adress, $request, $users)) {
            // Redirection avec un message
            $message = 'L\'adresse a bien été créée';
            if ($this->getUser()) {
                // Redirection vers la page de commande si l'utilisateur est connecté
                return $this->redirectToRoute('app_commande', [
                    'message' => '1'
                ]);
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('FrontOffice/order/new.html.twig', [
            'title' => 'adresse',
            'message' => $message,
            'flag' => false,
            'adresse' => $adress,
        ]);
    }

    //Page de création d'adresse
    #[Route('/order/create_adresse', name: 'app_create_adresse_commande')]
    public function createOrderAdress(
        Request       $request,
        AdressService $adressService,
        Security      $security
    ): Response
    {
        // Instanciation d'un nouvel objet Adress
        $adress = new Adress();
        return $this->formOrderAdress($adress, $request, $adressService, $security);
    }
}