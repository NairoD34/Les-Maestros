<?php

namespace App\Service\FrontOffice;

use App\Entity\Adress;
use App\Entity\Users;
use App\Repository\AdressRepository;
use App\Repository\ZIPcodeRepository;
use App\Repository\CityRepository;
use Symfony\Component\HttpFoundation\Request;

class AdressService
{
    public function __construct(
        private readonly AdressRepository  $adressRepo,
        private readonly ZIPcodeRepository $zipRepo,
        private readonly CityRepository    $cityRepo,
    )
    {
    }

    /**
     * Save the adresse from user's form.
     * Returns true once the adress gets saved, else return false
     */
    public function SaveAdressForm(
        ?Adress $adress,
        Request $request,
        ?Users  $users
    ): bool
    {
        if ($request->isMethod('POST') && $request->request->has('submitAdresse')) {
            $adress->setNumber($request->request->get('num_voie'));
            $adress->setStreet($request->request->get('rue'));
            $adress->setIsActive(true);
            $adress->setComplement($request->request->get('complement'));

            if ($users) {
                $adress->setUsers($users);
            }

            $city = $this->cityRepo->find($request->request->get('villeId'));
            $adress->setCity($city);
            $codePostalId = $this->zipRepo->find($request->request->get('selectedPostalCodesId'));
            $adress->setZIPcode($codePostalId);

            $this->adressRepo->save($adress, true);

            return true;
        }
        return false;
    }

    public function AdressList(
        Request $request,
        Users   $users,
    )
    {
        $allAdresses = $this->adressRepo->findBy(['users' => $users]);
        $filteredAdresses = [];
        $street = $request->query->get('rue', '');
        if ($street) {
            foreach ($allAdresses as $adress) {
                if (stripos($adress->getStreet(), $street) !== false) {
                    $filteredAdresses[] = $adress;
                }
            }
        } else {
            $filteredAdresses = $allAdresses;
        }

        return [
            'rue' => $street,
            'filteredAdresses' => $filteredAdresses,
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

            foreach ($city->getZIPcode() as $codePostal) {
                $codesPostauxArray[] = [
                    'id' => $codePostal->getId(),
                    'libelle' => $codePostal->getTitle()
                ];
            }
            $json[] = [
                'id' => $city->getId(),
                'ville' => $city->getName(),
                'codeDepartement' => $city->getCounty()->getName(),
                'region' => $city->getCounty()->getRegion()->getName(),
                'codePostaux' => $codesPostauxArray,
            ];
        }
        return $json;
    }
}