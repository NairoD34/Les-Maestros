<?php

namespace App\Controller\FrontOffice;

use App\Repository\ProductRepository;
use App\Service\FrontOffice\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Contrôleur pour afficher la page d'accueil.
class HomePageController extends AbstractController
{
    //Affiche la page d'accueil.
    #[Route('/', name: 'app_index')]
    public function index(
        ProductRepository $productRepo,
        Request           $request,
        CategoryService   $categoryService,
    ): Response
    {
        //Recherche des produits en promo
        $products = $productRepo->findTopSalesProducts();
        $dataPromo = $categoryService->getProducts($products);

        //Recherche des nouveaux produits
        $newProducts = $productRepo->searchNew();
        $dataNewProduct = $categoryService->getProducts($newProducts);

        //Recherche des catégories avec leurs photos
        $dataCate = $categoryService->CategoryPicture($request);

        return $this->render('FrontOffice/homePage/indexHomePage.html.twig', [
            'title' => 'MSymfony',
            'subtitle' => 'La musique, c\'est notre passion, les promotions, c\'est notre métier',
            'data' => $dataPromo,
            'dataNew' => $dataNewProduct,
            'dataCate' => $dataCate
        ]);
    }
}

