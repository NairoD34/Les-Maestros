<?php

namespace App\Controller\FrontOffice;

use App\Entity\Adress;
use App\Service\FrontOffice\AdressService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPanelAdresseController extends AbstractController
{
    //Affichage de la liste des adresses
    #[Route('/user/list_address', name: 'app_list_adresse')]
    public function listAddress(
        Request       $request,
        Security      $security,
        AdressService $adressService,
    ): Response
    {
        //Recuperation de l'utilisateur connecté
        $users = $security->getUser();
        //Appel du service pour obtenir la liste des adresses
        $result = $adressService->AdressList($request, $users);
        return $this->render('FrontOffice/user/list.html.twig', [
            'title' => 'Liste de vos adresses',
            'adresses' => $result['filteredAdresses'],
            'rue' => $result['rue'],
        ]);
    }

    //Affichage d'une adresse
    #[Route('/user/address/{id}', name: 'app_show_adresse')]
    public function showAddress(?Adress $adress)
    {
        //Recuperation de l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            // Redirige vers la page de connexion si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_login');
        }

        if (!$adress) {
            //Affiche un message d'erreur si l'adresse n'existe pas et redirige vers la page de creation d'adresse
            $this->addFlash('error', 'Adresse non trouvé.');
            return $this->redirectToRoute('app_create_adresse');
        }

       
        return $this->render('FrontOffice/user/showAdresse.html.twig', [
            'title' => 'Information de l\'adresse ',
            'adresse' => $adress,
            'user' => $user

        ]);
    }

    //Desactivation d'une adresse
    #[Route('/user/desactivate_address/{id}', name: 'app_desactivate_adresse')]
    public function desactivateAddress(
        ?Adress                $adress,
        EntityManagerInterface $em
    ): Response
    {

        //Recuperation de l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            // Redirige vers la page de connexion si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_login');
        }

        //Verification que l'adresse existe
        if (!$adress) {
            //Affiche un message d'erreur si l'adresse n'existe pas et redirige vers la page de creation d'adresse
            $this->addFlash('error', 'Adresse non trouvé.');
            return $this->redirectToRoute('app_user');
        }

        //Verification que l'adresse est active
        if (!$adress->isIsActive()) {
            //Affiche un message d'erreur si l'adresse n'est pas active et redirige vers la page des adresses
            $this->addFlash('warning', 'L\'adresse est déjà inactive.');
            return $this->redirectToRoute('app_list_adresse');
        }

        //Modification de l'etat de l'adresse
        $adress->setIsActive(false);
        $em->persist($adress);
        $em->flush();
        return $this->redirectToRoute('app_list_adresse');
    }

    //Suppression d'une adresse
    #[Route('/user/delete_address/{id}', name: 'app_delete_adresse')]
    public function deleteAddress(
        ?Adress                $adress,
        EntityManagerInterface $em
    ): Response
    {

        //Recuperation de l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            // Redirige vers la page de connexion si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_login');
        }

        //Verification que l'adresse existe
        if (!$adress) {
            //Affiche un message d'erreur si l'adresse n'existe pas et redirige vers la page des adresses
            $this->addFlash('error', 'Adresse non trouvé.');
            return $this->redirectToRoute('app_list_adresse');
        }

        //Suppression de l'adresse
        $em->remove($adress);
        $em->flush();
        return $this->redirectToRoute('app_list_adresse');
    }

    //Reactivation d'une adresse
    #[Route('/user/reactivate_address/{id}', name: 'app_reactivate_adresse')]
    public function reactivateAdresse(
        ?Adress                $adress,
        EntityManagerInterface $em
    ): Response
    {

        //Recuperation de l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            // Redirige vers la page de connexion si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_login');
        }

        //Verification que l'adresse existe
        if (!$adress) {
            //Affiche un message d'erreur si l'adresse n'existe pas et redirige vers la page des adresses
            $this->addFlash('error', 'Adresse non trouvé.');
            return $this->redirectToRoute('app_list_adresse');
        }

        //Verification que l'adresse est active
        if ($adress->isIsActive()) {
            //Affiche un message d'erreur si l'adresse est active et redirige vers la page des adresses
            $this->addFlash('warning', 'L\'adresse est déjà active.');
            return $this->redirectToRoute('app_list_adresse');
        }

        //Activation de l'adresse
        $adress->setIsActive(true);
        $em->persist($adress);
        $em->flush();


        //Affichage du message de succès
        $this->addFlash('success', 'L\'adresse a été réactivée avec succès.');

        return $this->redirectToRoute('app_list_adresse');
    }

    //Affichage du Formulaire pour l'entité Adresse
    private function formAddress(
        ?Adress       $adress,
        Request       $request,
        Security      $security,
        AdressService $adressService,
                      $isUpdate = false,
    )
    {
        $message = '';
        $users = $security->getUser(); //Récupère l'utilisateur connecté
        if ($adressService->SaveAdressForm($adress, $request, $users)) { //True|False adresse sauvegardée ?

            if ($request->get('id')) { //Si l'adresse existait déjà, renvoie vers la liste
                $this->addFlash("succes", "l\'adresse a bien été modifiée");
                return $this->redirectToRoute('app_list_adresse');
            }

            $this->addFlash("succes", 'L\'adresse a bien été créée');
            if ($this->getUser()) { //Si l'utilisateur est bien connecté
                return $this->redirectToRoute('app_list_adresse');
            }
            return $this->redirectToRoute('app_login');
        }

        //Render pour le template du formulaire d'adresses
        return $this->render('FrontOffice/user/new.html.twig', [
            'title' => 'adresse',
            'message' => $message,
            'flag' => $isUpdate,
            'adresse' => $adress,
            'users' => $users,
        ]);
    }

    //Page de création d'adresse
    #[Route('/user/create_address', name: 'app_create_adresse')]
    public function createAdresse(
        Request       $request,
        Security      $security,
        AdressService $adressService,
    ): Response
    {
        //Instanciation d'un nouvel objet Adress
        $adresse = new Adress();
        return $this->formAddress($adresse, $request, $security, $adressService);
    }

    //Page de modification d'adresse
    #[Route('/user/update_address/{id}', name: 'app_update_adresse')]
    public function updateAdresse(
        Adress        $adress,
        Request       $request,
        Security      $security,
        AdressService $adressService,
    ): Response
    {
        //Recuperation de l'utilisateur connecté
        $users = $this->getUser();
        //Appel du formulaire
        return $this->formAddress($adress, $request, $security, $adressService, true);
    }

    //Route pour la recherche de ville
    #[Route('/adresse/ajax/ville/{name}', name: 'ajax_ville')]
    public function ajaxCity(
        Request       $request,
        AdressService $adressService,
    ): Response
    {
        //Appel du service
        return new JsonResponse($adressService->ReturnJsonCity($request), 200);
    }
}