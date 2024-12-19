<?php

namespace App\Controller\FrontOffice;

namespace App\Service\FrontOffice;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProductRepository;

class ProductService
{
    public function __construct(private readonly PhotosRepository $photoRepository, private readonly CategoryRepository $categoryRepository, private readonly ProductRepository $productRepository)

    {
    }

    public function getDetailsAboutProduct($product): array
    {
        $mainProductDetails = $this->getProductPriceDetails($product);

        $photos = $this->photoRepository->searchPhotoByProduct($product);
        $category = $product->getCategory();
        $parentCategory = $this->categoryRepository->findParentCategoryIdByChildId($category->getId());
        $newProducts = $this->productRepository->searchNew();

        $randomProducts = $this->productRepository->getSixRandomProducts();
        $randomProductDetails = array_map(fn($randProduct) => [
            'details' => $this->getProductPriceDetails($randProduct),
            'photos' => $this->photoRepository->searchPhotoByProduct($randProduct),
            'category' => $randProduct->getCategory(),
        ], $randomProducts);

        return [
            'title' => 'Fiche d\'un produit',
            'mainProduct' => [
                'details' => $mainProductDetails,
                'photos' => $photos,
                'category' => $category,
                'parentCategory' => $parentCategory,
            ],
            'newProducts' => $newProducts,
            'randomProducts' => $randomProductDetails,
        ];
    }

    private function getProductPriceDetails($product): array
    {
        $priceTTC = $product->getTaxFreePrice() + ($product->getTaxFreePrice() * $product->getTaxRate()->getTaxRate() / 100);

        $priceTTCFormatted = number_format($priceTTC, 2, '.', '');


        if ($product->getSales()) {
            $newTtcPrice = $priceTTCFormatted * $product->getSales()->getSalesRate();
            $priceTTCFormatted = number_format($newTtcPrice, 2, '.', '');
        }

        $oldPrice = $product->getTaxFreePrice() + ($product->getTaxFreePrice() * $product->getTaxRate()->getTaxRate() / 100);
        $oldPriceFormatted = number_format($oldPrice, 2, '.', '');


        return [
            "products" => $product,
            'priceTTC' => $priceTTCFormatted,
            'oldPrice' => $oldPriceFormatted,
        ];
    }


}