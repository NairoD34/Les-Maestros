<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Users;
use App\Repository\VilleRepository;
use App\Service\FrontOffice\AdressService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPanelAdresseController extends AbstractController
{
    #[Route('/user/list_adresse', name: 'app_list_adresse')]
    public function listAdresse(
        Request $request,
        Users $users,
        AdressService $adressService,
        ): Response
    {
        $result = $adressService->AdressList($request, $users);

        return $this->render('user/list.html.twig', [
            'title' => 'Liste de vos adresses',
            'adresses' => $result['$filteredAdresses'],
            'rue' => $result['rue'],
        ]);
    }

    #[Route('/user/adresse/{id}', name: 'app_show_adresse')]
    public function showAdresse(?Adresse $adresse)
    {
        if ($adresse === null) {
            return $this->redirectToRoute('app_create_adresse');
        }
        $user = $this->getUser();

        return $this->render('user/showAdresse.html.twig', [
            'title' => 'Information de l\'adresse ',
            'adresse' => $adresse,
            'user' => $user

        ]);
    }
    #[Route('/user/desactivate_adresse/{id}', name: 'app_desactivate_adresse')]
    public function desactivateAdresse(
        Adresse $adresse,
        EntityManagerInterface $em
    ): Response {

        if ($adresse === null) {
            return $this->redirectToRoute('app_user');
        }
        if (!$adresse->isIsActive()) {
            // Vous pouvez rediriger l'utilisateur ou renvoyer une réponse indiquant que l'adresse est déjà inactive.
            $this->addFlash('warning', 'L\'adresse est déjà inactive.');
            return $this->redirectToRoute('app_list_adresse'); // Adaptez cette route selon vos besoins.
        }
        $adresse->setIsActive(false);
        $em->persist($adresse);
        $em->flush();
        return $this->redirectToRoute('app_list_adresse');
    }

    #[Route('/user/delete_adresse/{id}', name: 'app_delete_adresse')]
    public function deleteAdresse(
        Adresse $adresse,
        EntityManagerInterface $em
    ): Response {

        if ($adresse === null) {
            return $this->redirectToRoute('app_user');
        }
       
        $em->remove($adresse);
        $em->flush();
        return $this->redirectToRoute('app_list_adresse');
    }

    #[Route('/user/reactivate_adresse/{id}', name: 'app_reactivate_adresse')]
    public function reactivateAdresse(
        Adresse $adresse,
        EntityManagerInterface $em
        ):Response
    {
        
        if ($adresse->isIsActive()) {
            
            $this->addFlash('warning', 'L\'adresse est déjà active.');
            return $this->redirectToRoute('app_list_adresse'); 
        }

        $adresse->setIsActive(true);
        $em->persist($adresse);
        $em->flush();

       
        $this->addFlash('success', 'L\'adresse a été réactivée avec succès.');

        return $this->redirectToRoute('app_list_adresse'); 
}

    //Affichage Formulaire pour l'entité Adresse
    private function formAdresse(
        Adresse $adresse, 
        Request $request, 
        Users $users, 
        $isUpdate = false,
        AdressService $adressService,
        )
    {
        $message = '';

        if ($adressService->SaveAdressForm($adresse, $users, $request)) {

            if ($request->get('id')) {
                $this->addFlash("succes","l\'adresse a bien été modifiée");
                return $this->redirectToRoute('app_list_adresse');
            } else {
                $this->addFlash("succes",'L\'adresse a bien été créée');
                if ($this->getUser()) {
                    return $this->redirectToRoute('app_list_adresse');
                } else {
                    return $this->redirectToRoute('app_login');
                }
            }
        }
        return $this->render('user/new.html.twig', [
            'title' => 'adresse',
            'message' => $message,
            'flag' => $isUpdate,
            'adresse' => $adresse,
            'users' => $users,
        ]);
    }

    //Page de création d'adresse
    #[Route('/user/create_adresse', name: 'app_create_adresse')]
    public function createAdresse(
        Request $request,
        AdressService $adressService,
        ): Response
    {
        $users = $this->getUser();
        $adresse = new Adresse();
        return $this->formAdresse($adresse, $request, $users, false, $adressService);
    }

    //Page de modification d'adresse
    #[Route('/user/update_adresse/{id}', name: 'app_update_adresse')]
    public function updateAdresse(
        Adresse $adresse, 
        Request $request, 
        AdressService $adressService,
        ): Response
    {
        $users = $this->getUser();
        return $this->formAdresse($adresse, $request, $users, true, $adressService);
    }

    #[Route('/adresse/ajax/ville/{name}', name: 'ajax_ville')]
    public function ajaxCity(
        Request $request,
        AdressService $adressService,
        ): Response
    {/* 
        $string = $request->get('name');
        $cities = $cityRepo->searchByName($string);
        $json = [];
        foreach ($cities as $city) {
            $codesPostauxArray = [];

            foreach ($city->getCodePostal() as $codePostal) {
                $codesPostauxArray[] = [
                    'id' => $codePostal->getId(),
                    'libelle' => $codePostal->getLibelle()
                ];
            }
            $json[] = [
                'id' => $city->getId(),
                'ville' => $city->getNom(),
                'codeDepartement' => $city->getDepartement()->getNom(),
                'region' => $city->getDepartement()->getRegion()->getNom(),
                'codePostaux' => $codesPostauxArray,
            ];
        } */

        return new JsonResponse($adressService->ReturnJsonCity($request), 200);
    }
}