<?php

namespace App\Service\FrontOffice;

use App\Entity\Adresse;
use App\Entity\Users;
use App\Repository\AdresseRepository;
use App\Repository\CodePostalRepository;
use App\Repository\VilleRepository;
use Symfony\Component\HttpFoundation\Request;

class AdressService
{
    public function __construct(
        private VilleRepository $villeRepo,
        private AdresseRepository $adresseRepo,
        private CodePostalRepository $codePostalRepo,
        private VilleRepository $cityRepo,
    )
    {
    }

    /**
     * Save the adresse from user's form.
     * Returns true once the adress gets saved, else return false
     */
    public function SaveAdressForm(
        ?Adresse $adresse,
        Users $users,
        Request $request,
        ):bool
    {
        if (isset($_POST['submitAdresse'])) {
            $adresse->setNumVoie($request->request->get('num_voie'));
            $adresse->setRue($request->request->get('rue'));
            $adresse->setIsActive(true);
            $adresse->setComplement($request->request->get('complement'));
            $users = $this->getUser();
            $adresse->setUsers($users);
            $ville = $this->villeRepo->find($request->request->get('villeId'));
            $adresse->setVille($ville);
            $codePostalId = $this->codePostalRepo->find($request->request->get('selectedPostalCodesId'));
            $adresse->setCodePostal($codePostalId);

            $this->adresseRepo->save($adresse, true);

            return true;
        }
        return false;
    }

    public function AdressList(
        Request $request,
        Users $users,
        )
    {
        // Récupérer toutes les adresses de l'utilisateur
        $allAdresses = $this->adresseRepo->findBy(['users' => $users]);

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

        return [
            'rue' => $rue,
            '$filteredAdresses' => $filteredAdresses,
        ];
    }

    public function ReturnJsonCity(
        Request $request,
    )
    {        
        $string = $request->get('name');
        $cities = $this->cityRepo->searchByName($string);
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
        return $json;
    }
}