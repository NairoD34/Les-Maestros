<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(
        ProduitRepository $produitRepo,
        PhotosRepository $photoRepo,
        CategorieRepository $cateRepo,
        Request $request,
    ): Response {
        $produits = $produitRepo->findTopPromoProducts();

        $dataPromo = [];

        foreach ($produits as $produit) {
            $prixTTC = $produit->getPrixHT() + ($produit->getPrixHT() * $produit->getTVA()->getTauxTva() / 100);
            // Vérifiez si le produit a une promotion
            if ($produit->getPromotion() !== null) {
                $prixTTC = $prixTTC * $produit->getPromotion()->getTauxPromotion();
            }
            $prixTTC = number_format($prixTTC, 2, '.', '');
            $oldPrice = $produit->getPrixHT() + ($produit->getPrixHT() * $produit->getTVA()->getTauxTva() / 100);
            $oldPrice = number_format($oldPrice, 2, '.', '');
            $photos = $photoRepo->searchPhotoByProduit($produit);
            $dataPromo[] = [
                'produit' => $produit,
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
        $dataCate = [];
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
