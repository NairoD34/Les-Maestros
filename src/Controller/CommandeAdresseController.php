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
        ?Adresse $adresse, 
        Request $request, 
        Users $users, 
        $isUpdate = false,
        AdressService $adresseService,
        )
    {
        $message = '';
        
        $adresseService->SaveAdressForm($adresse, $users, $request);

        return $this->render('commande/new.html.twig', [
            'title' => 'adresse',
            'message' => $message,
            'flag' => $isUpdate,
            'adresse' => $adresse,
            'users' => $users,
        ]);        
    }

    //Page de création d'adresse
    #[Route('/commande/create_adresse', name: 'app_create_adresse_commande')]
    public function createCommandeAdresse(
        Request $request,
        AdressService $adresseService,
        ): Response
    {
        $users = $this->getUser();
        $adresse = new Adresse();
        return $this->formCommandeAdresse($adresse, $request, $users, false, $adresseService);
    }
}
