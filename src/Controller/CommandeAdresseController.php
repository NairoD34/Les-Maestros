<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Users;
use App\Service\FrontOffice\AdressService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeAdresseController extends AbstractController
{
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
