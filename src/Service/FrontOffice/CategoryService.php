<?php


namespace App\Service\FrontOffice;

use App\Repository\CategoryRepository;
use App\Repository\PhotosRepository;
use Symfony\Component\HttpFoundation\Request;

// Service pour gérer les catégories dans le front-office.
class CategoryService
{

//    private $photosRepository;
    public function __construct(private readonly PhotosRepository $photosRepo, private readonly CategoryRepository $cateRepo)
    {
    }

    /**
     * Return array with products datas
     */
    // Methode pour obtenir les données des produits.
    public function getProducts($products): array
    {
        $productsData = [];
        foreach ($products as $product) {
            $prixTTC = $product->getTaxFreePrice() + ($product->getTaxFreePrice() * $product->getTaxRate()->getTaxRate() / 100);

            if ($product->getSales()) {
                $prixTTC *= $product->getSales()->getSalesRate();
            }
            $prixTTC = number_format($prixTTC, 2, '.', '');

            $oldPrice = $product->getTaxFreePrice() + ($product->getTaxFreePrice() * $product->getTaxRate()->getTaxRate() / 100);
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

    /**
     * Return array with categories datas
     */
    // Methode pour obtenir les photos des catégories.
    public function CategoryPicture(
        Request $request,
    ): array
    {
        $dataCate = [];
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
                'photos' => $photoURL ?? "",
            ];
        }
        return $dataCate;
    }
}