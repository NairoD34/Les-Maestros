<?php

namespace App\Controller\FrontOffice;

use App\Repository\CategoryRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(
        ProductRepository $productRepo,
        PhotosRepository $photoRepo,
        CategoryRepository $cateRepo,
        Request $request,
    ): Response {
        $prodcts = $productRepo->findTopPromoProducts();

        $dataSales = [];

        foreach ($products as $product) {
            $TIPrice = $product->getPrixHT() + ($product->getPrixHT() * $product->getTVA()->getTauxTva() / 100);
            // Vérifiez si le produit a une promotion
            if ($product->getPromotion() !== null) {
                $prixTTC = $TIPrice * $product->getSales()->getSalesRate();
            }
            $TIPrice = number_format($prixTTC, 2, '.', '');
            $oldPrice = $product->getPrixHT() + ($product->getPrixHT() * $product->getTVA()->getTauxTva() / 100);
            $oldPrice = number_format($oldPrice, 2, '.', '');
            $photos = $photoRepo->searchPhotoByProduit($product);
            $dataPromo[] = [
                'produit' => $product,
                'prixTTC' => $prixTTC,
                'photos' => $photos,
                'oldPrice' => $oldPrice,

            ];
        }
        $newProducts = $produitRepo->searchNew();
        $dataNewProduct = [];

        foreach ($newProducts as $product) {
            $prixTTCNew = $product->getPrixHT() + ($product->getPrixHT() * $product->getTVA()->getTauxTva() / 100);

            // Vérifiez si le produit a une promotion
            if ($product->getPromotion() !== null) {
                $prixTTCNew = $prixTTCNew * $product->getPromotion()->getTauxPromotion();
            }

            $prixTTCNew = number_format($prixTTCNew, 2, '.', '');

            $oldPriceNew = $product->getPrixHT() + ($product->getPrixHT() * $product->getTVA()->getTauxTva() / 100);
            $oldPriceNew = number_format($oldPriceNew, 2, '.', '');

            $photosNew = $photoRepo->searchPhotoByProduit($product);

            $dataNewProduct[] = [
                'produit' => $product,
                'prixTTC' => $prixTTCNew,
                'photos' => $photosNew,
                'oldPrice' => $oldPriceNew,
            ];
        }
        $categories = $cate = $cateRepo->searchCategorieParente(
            $request->query->get('libelle', ''),

        );
        foreach ($categories as $cate) {

            $photoCate = $photoRepo->searchPhotoByCategorie($cate);
            foreach ($photoCate as $photo) {
                $photoURL = $photo->getURLPhoto();
            }
            $dataCate[] = [
                'categorie' => $cate,
                'photos' => $photoURL,
            ];
        }



        return $this->render('homepage/indexHomePage.html.twig', [
            'title' => 'MSymfony',
            'subtitle' => 'La musique, c\'est notre passion, les promotions, c\'est notre métier',
            'data' => $dataPromo,
            'dataNew' => $dataNewProduct,
            'dataCate' => $dataCate
        ]);
    }
}
