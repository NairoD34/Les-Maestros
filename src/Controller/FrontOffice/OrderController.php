<?php

namespace App\Controller\FrontOffice;

use App\Entity\Adress;
use App\Entity\Users;
use App\Service\FrontOffice\AdressService;
use App\Service\FrontOffice\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function NewCommand(
        Security    $security,
        CartService $cartService,
        Request     $request,
    ): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        $total = $cartService->CalculCart()['total'];

        if ($cartService->FormOrdersValidation($request)) {

            $this->addFlash('success', 'Votre commande a bien été validée.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('commande/index.html.twig', [
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
            $message = 'L\'adresse a bien été créée';
            if ($this->getUser()) {
                return $this->redirectToRoute('app_commande', [
                    'message' => '1'
                ]);
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('commande/new.html.twig', [
            'title' => 'adresse',
            'message' => $message,
            'flag' => false,
            'adresse' => $adress,
        ]);
    }

    //Page de création d'adresse
    #[Route('/commande/create_adresse', name: 'app_create_adresse_commande')]
    public function createOrderAdress(
        Request       $request,
        AdressService $adressService,
        Security      $security
    ): Response
    {
        $adress = new Adress();
        return $this->formOrderAdress($adress, $request, $adressService, $security);
    }
}