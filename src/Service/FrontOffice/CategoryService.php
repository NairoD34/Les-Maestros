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

    public function CategoryPicture(
        Request $request,
    ): array
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