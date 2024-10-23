<?php

namespace App\Service\FrontOffice\CategorieService;

use App\Repository\PhotosRepository;

class CategorieService
{
//    private $photosRepository;
    public function __construct(private readonly PhotosRepository $photosRepo)
    {
//            $this->photosRepository = $photosRepo;
    }

    public function getProducts(array $products): array
    {

        $productsData = [];
        foreach ($products as $product) {
            $prixTTC = $product->getPrixHT() + ($product->getPrixHT() * $product->getTVA()->getTauxTva() / 100);

            if ($product->getPromotion() !== null) {
                $prixTTC *= $product->getPromotion()->getTauxPromotion();

            }
            $prixTTC = number_format($prixTTC, 2, '.', '');

            $oldPrice = $product->getPrixHT() + ($product->getPrixHT() * $product->getTVA()->getTauxTva() / 100);
            $oldPrice = number_format($oldPrice, 2, '.', '');
            $photosProducts = $this->photosRepo->searchPhotoByProduit($product);
            $productsData[] = [
                'produit' => $product,
                'prixTTC' => $prixTTC,
                'oldPrice' => $oldPrice,
                'photos' => $photosProducts,
            ];
        }

        return $productsData;
    }


}