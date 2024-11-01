<?php

namespace App\Controller\FrontOffice;

use App\Entity\Adress;
use App\Entity\Users;
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
    #[Route('/user/list_address', name: 'app_list_adresse')]
    public function listAddress(
        Request       $request,
        Users         $users,
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

    #[Route('/user/address/{id}', name: 'app_show_adresse')]
    public function showAddress(?Adress $adress)
    {
        if (!$adress) {
            return $this->redirectToRoute('app_create_adresse');
        }
        $user = $this->getUser();

        return $this->render('user/showAdresse.html.twig', [
            'title' => 'Information de l\'adresse ',
            'adresse' => $adress,
            'user' => $user

        ]);
    }

    #[Route('/user/desactivate_address/{id}', name: 'app_desactivate_adresse')]
    public function desactivateAddress(
        ?Adress                $adress,
        EntityManagerInterface $em
    ): Response
    {

        if (!$adress) {
            return $this->redirectToRoute('app_user');
        }
        if (!$adress->isIsActive()) {
            $this->addFlash('warning', 'L\'adresse est déjà inactive.');
            return $this->redirectToRoute('app_list_adresse');
        }
        $adress->setIsActive(false);
        $em->persist($adress);
        $em->flush();
        return $this->redirectToRoute('app_list_adresse');
    }

    #[Route('/user/delete_address/{id}', name: 'app_delete_adresse')]
    public function deleteAddress(
        ?Adress                $adress,
        EntityManagerInterface $em
    ): Response
    {

        if (!$adress) {
            return $this->redirectToRoute('app_user');
        }

        $em->remove($adress);
        $em->flush();
        return $this->redirectToRoute('app_list_adresse');
    }

    #[Route('/user/reactivate_address/{id}', name: 'app_reactivate_adresse')]
    public function reactivateAdresse(
        ?Adress                $adress,
        EntityManagerInterface $em
    ): Response
    {

        if ($adress->isIsActive()) {
            $this->addFlash('warning', 'L\'adresse est déjà active.');
            return $this->redirectToRoute('app_list_adresse');
        }

        $adress->setIsActive(true);
        $em->persist($adress);
        $em->flush();


        $this->addFlash('success', 'L\'adresse a été réactivée avec succès.');

        return $this->redirectToRoute('app_list_adresse');
    }

    //Affichage Formulaire pour l'entité Adresse
    private function formAddress(
        ?Adress       $adress,
        Request       $request,
        Security      $security,
        AdressService $adressService,
                      $isUpdate = false,
    )
    {
        $message = '';
        $users = $security->getUser();
        if ($adressService->SaveAdressForm($adress, $request, $users)) {

            if ($request->get('id')) {
                $this->addFlash("succes", "l\'adresse a bien été modifiée");
                return $this->redirectToRoute('app_list_adresse');
            }

            $this->addFlash("succes", 'L\'adresse a bien été créée');
            if ($this->getUser()) {
                return $this->redirectToRoute('app_list_adresse');
            }

            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/new.html.twig', [
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
        $users = $this->getUser();
        return $this->formAddress($adress, $request, $security, $adressService, true);
    }

    #[Route('/adresse/ajax/ville/{name}', name: 'ajax_ville')]
    public function ajaxCity(
        Request       $request,
        AdressService $adressService,
    ): Response
    {
        return new JsonResponse($adressService->ReturnJsonCity($request), 200);
    }
}