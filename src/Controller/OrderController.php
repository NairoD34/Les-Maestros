<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Users;
use App\Service\FrontOffice\AdressService;
use App\Service\FrontOffice\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function NewCommande(
    Security $security,
    PanierService $panierService,
    Request $request,
    ): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        $total = $panierService->CalculPanier()['total'];

        if($panierService->FormCommandeValidation($request)){
        
            $this->addFlash('success', 'Votre commande a bien été validée.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('commande/index.html.twig', [
            'controller_name' => 'OrderController',
            'form' => $panierService->GetUserData()['form'],
            'totalttc' => $total,
        ]);
    }

    //Affichage Formulaire pour l'entité Adresse
    private function formCommandeAdresse(
        ?Adresse $adress, 
        Request $request, 
        Users $users, 
        $isUpdate = false,
        AdressService $adressService,
        )
    {
        $message = '';
        
        $adressService->SaveAdressForm($adress, $users, $request);

        return $this->render('commande/new.html.twig', [
            'title' => 'adresse',
            'message' => $message,
            'flag' => $isUpdate,
            'adresse' => $adress,
            'users' => $users,
        ]);        
    }

    //Page de création d'adresse
    #[Route('/commande/create_adresse', name: 'app_create_adresse_commande')]
    public function createCommandeAdresse(
        Request $request,
        AdressService $adressService,
        ): Response
    {
        $users = $this->getUser();
        $adress = new Adresse();
        return $this->formCommandeAdresse($adress, $request, $users, false, $adressService);
    }
}