<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Service\FrontOffice\CategoryService;
use App\Service\FrontOffice\ProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(
        ProduitRepository $produitRepo,
        Request $request,
        ProductsService $productsService,
        CategoryService $categoryService,
    ): Response
    {
        $products = $produitRepo->findTopPromoProducts();
        $dataPromo = $productsService->getProducts($products);
                
        $newProducts = $produitRepo->searchNew();
        $dataNewProduct = $productsService->getProducts($newProducts);
        
        $dataCate = $categoryService->CategoryPicture($request);

        return $this->render('homepage/indexHomePage.html.twig', [
            'title' => 'MSymfony',
            'subtitle' => 'La musique, c\'est notre passion, les promotions, c\'est notre métier',
            'data' => $dataPromo,
            'dataNew' => $dataNewProduct,
            'dataCate' => $dataCate
        ]);
    }
}