<?php

namespace App\Controller\FrontOffice;

use App\Entity\Adresse;
use App\Entity\Users;
use App\Repository\AdresseRepository;
use App\Repository\CodePostalRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class UserPanelAdresseController extends AbstractController
{

    #[Route('/user/list_adresse', name: 'app_list_adresse')]
    public function listAdresse(AdresseRepository $adresseRepo, Request $request): Response
    {
        // Récupérer l'utilisateur actuellement authentifié
        $user = $this->getUser();

        // Récupérer toutes les adresses de l'utilisateur
        $allAdresses = $adresseRepo->findBy(['users' => $user]);

        // Filtrer les adresses par nom de rue si une valeur est fournie
        $filteredAdresses = [];
        $rue = $request->query->get('rue', '');
        if ($rue) {
            foreach ($allAdresses as $adresse) {
                if (stripos($adresse->getRue(), $rue) !== false) {
                    $filteredAdresses[] = $adresse;
                }
            }
        } else {
            $filteredAdresses = $allAdresses;
        }
        return $this->render('user/list.html.twig', [
            'title' => 'Liste de vos adresses',
            'adresses' => $filteredAdresses,
            'rue' => $rue,
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
        Security $security,
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
        Security $security,
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
    public function reactivateAdresse(Adresse $adresse,
    Security $security,
    EntityManagerInterface $em): Response
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
    private function formAdresse(Adresse $adresse, AdresseRepository $adresseRepo, CodePostalRepository $codePostalRepo, Request $request, Users $users, VilleRepository $villeRepo, $isUpdate = false)
    {
        $message = '';



        if (isset($_POST['submitAdresse'])) {
            $adresse->setNumVoie($request->request->get('num_voie'));
            $adresse->setRue($request->request->get('rue'));
            $adresse->setComplement($request->request->get('complement'));
            $adresse->setIsActive(true);
            $users = $this->getUser();
            $adresse->setUsers($users);
            $ville = $villeRepo->find($request->request->get('villeId'));
            $adresse->setVille($ville);
            $codePostalId = $codePostalRepo->find($request->request->get('selectedPostalCodesId'));
            $adresse->setCodePostal($codePostalId);

            $adresseRepo->save($adresse, true);

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
    public function createAdresse(AdresseRepository $adresseRepo, CodePostalRepository $codePostalRepo, Request $request, VilleRepository $villeRepo): Response
    {
        $users = $this->getUser();
        $adresse = new Adresse();
        return $this->formAdresse($adresse, $adresseRepo, $codePostalRepo, $request, $users, $villeRepo, false);
    }

    //Page de modification d'adresse
    #[Route('/user/update_adresse/{id}', name: 'app_update_adresse')]
    public function updateAdresse(Adresse $adresse, AdresseRepository $adresseRepo, CodePostalRepository $codePostalRepo, Request $request, VilleRepository $villeRepo): Response
    {
        $users = $this->getUser();
        return $this->formAdresse($adresse, $adresseRepo, $codePostalRepo, $request, $users,  $villeRepo, true);
    }

    #[Route('/adresse/ajax/ville/{name}', name: 'ajax_ville')]
    public function ajaxCity(VilleRepository $cityRepo, Request $request): Response
    {
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
        }

        return new JsonResponse($json, 200);
    }
}
