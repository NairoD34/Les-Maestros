<?php

namespace App\Controller\FrontOffice;

use App\Entity\Adresse;
use App\Entity\Users;
use App\Repository\AdresseRepository;
use App\Repository\CodePostalRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeAdresseController extends AbstractController
{

    //Affichage Formulaire pour l'entité Adresse
    private function formCommandeAdresse(?Adresse $adresse, AdresseRepository $adresseRepo, CodePostalRepository $codePostalRepo, Request $request, Users $users, VilleRepository $villeRepo, $isUpdate = false)
    {
        $message = '';

        if (isset($_POST['submitAdresse'])) {
            $adresse->setNumVoie($request->request->get('num_voie'));
            $adresse->setRue($request->request->get('rue'));
            $adresse->setIsActive(true);
            $adresse->setComplement($request->request->get('complement'));
            $users = $this->getUser();
            $adresse->setUsers($users);
            $ville = $villeRepo->find($request->request->get('villeId'));
            $adresse->setVille($ville);
            $codePostalId = $codePostalRepo->find($request->request->get('selectedPostalCodesId'));
            $adresse->setCodePostal($codePostalId);

            $adresseRepo->save($adresse, true);

            if ($request->get('id')) {
            } else {
                $message = 'L\'adresse a bien été créée';
                if ($this->getUser()) {
                    return $this->redirectToRoute('app_commande', [
                        'message' => '1'
                    ]);
                } else {
                    return $this->redirectToRoute('app_login');
                }
            }
        }
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
    public function createCommandeAdresse(AdresseRepository $adresseRepo, CodePostalRepository $codePostalRepo, Request $request, VilleRepository $villeRepo): Response
    {
        $users = $this->getUser();
        $adresse = new Adresse();
        return $this->formCommandeAdresse($adresse, $adresseRepo, $codePostalRepo, $request, $users, $villeRepo, false);
    }


}
