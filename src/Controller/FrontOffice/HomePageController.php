<?php

namespace App\Controller\FrontOffice;

use App\Repository\ProductRepository;
use App\Service\FrontOffice\CategoryService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(
        ProductRepository $productRepo,
        Request           $request,
        CategoryService   $categoryService,
    ): Response
    {
        $products = $productRepo->findTopSalesProducts();
        $dataPromo = $categoryService->getProducts($products);

        $newProducts = $productRepo->searchNew();
        $dataNewProduct = $categoryService->getProducts($newProducts);

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