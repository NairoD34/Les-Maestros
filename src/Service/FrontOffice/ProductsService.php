<?php

namespace App\Service\FrontOffice;

use App\Repository\PhotosRepository;

class ProductsService
{
    public function __construct(
        private PhotosRepository $photoRepo,
    ) {
    
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
            $photosProducts = $this->photoRepo->searchPhotoByProduit($product);
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