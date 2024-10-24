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
}