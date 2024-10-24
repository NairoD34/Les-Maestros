<?php

namespace App\Controller;

use App\Service\FrontOffice\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
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

        $total = $panierService->CalculTotalPanier();

        if($panierService->FormCommandeValidation($request)){
        
            $this->addFlash('success', 'Votre commande a bien été validée.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'form' => $panierService->GetUserData()['form'],
            'totalttc' => $total,
        ]);
    }
}