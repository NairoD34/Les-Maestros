<?php


namespace App\Service\FrontOffice;

use App\Repository\CategoryRepository;
use App\Repository\PhotosRepository;
use Symfony\Component\HttpFoundation\Request;

class CategoryService
{

//    private $photosRepository;
    public function __construct(private readonly PhotosRepository $photosRepo, readonly CategoryRepository $cateRepo)
    {
    }

    public
    function getProducts(array $products): array
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

            $photosProducts = $this->photosRepo->searchPhotoByProduct($product);

            $productsData[] = [
                'produit' => $product,
                'prixTTC' => $prixTTC,
                'oldPrice' => $oldPrice,
                'photos' => $photosProducts,
            ];
        }
        return $productsData;
    }

    public function CategoryPicture(
        Request $request,
    )
    {
        $categories = $this->cateRepo->searchParentCategory(
            $request->query->get('title', ''),
        );

        foreach ($categories as $cate) {

            $photoCate = $this->photosRepo->searchPhotoByCategory($cate);
            foreach ($photoCate as $photo) {
                $photoURL = $photo->getURLPhoto();
            }
            $dataCate[] = [
                'categorie' => $cate,
                'photos' => $photoURL,
            ];
        }
        return $dataCate;
    }
}