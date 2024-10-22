<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProduitRepository;
use App\Service\CategorieService\CategorieService;
use ContainerMVcjxsa\getPhotosRepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{

    #[Route('/categorie', name: 'app_categorie')]
    public function index(CategorieRepository $caterepo): Response
    {
        $categories = $caterepo->findAll();
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $categories
        ]);
    }

    #[Route('/maincategorie/id={id}', name: 'app_categorie_show')]
    public function showCategorieParente(Categorie $cate, CategorieRepository $cateRepo, PhotosRepository $photoRepo)

    {
        if ($cate === null) {
            return $this->redirectToRoute('app_index');
        }
        $photo = $photoRepo->searchPhotoByCategorie($cate);

        $enfants = $cateRepo->searchCategorieEnfant($cate);

        return $this->render('categorie/showparent.html.twig', [
            'title' => 'Catégorie',
            'cate' => $cate,
            'enfants' => $enfants,
            'photos' => $photo
        ]);
    }

    #[Route('/enfant/{id}', name: 'app_categorie_show_enfant')]
    public function showCategorie(Categorie $cate)
    {
        if ($cate === null) {
            return $this->redirectToRoute('app_index');
        }


        return $this->render('categorie/showenfant.html.twig', [
            'title' => 'Catégorie',
            'cate' => $cate,

        ]);
    }

    #[Route('/produit_categorie/{id}', name: 'app_produit_categorie')]
    public function afficherProduitParCategorie(
        Categorie           $categories,
        ProduitRepository   $produitRepo,
        CategorieRepository $categorieRepo,
        PhotosRepository    $photoRepo,
        Produit             $produit,
        CategorieService    $categorieService
    ): Response
    {
        $categorieId = $categories->getId();
        $categorie = $categorieRepo->find($categorieId);
        $categorie_parente = $categorieRepo->findParentCategoryIdByChildId($categorieId);
        $products = $produitRepo->findProduitsByCategorieId($categorieId);
        $newsProducts = $produitRepo->searchNew();
        $photos = $photoRepo->searchPhotoByCategorie($categories);
//        $productsData = [];

//        foreach ($produits as $produit) {
//            $prixTTC = $produit->getPrixHT() + ($produit->getPrixHT() * $produit->getTVA()->getTauxTva() / 100);
//
//            if ($produit->getPromotion() !== null) {
//                $prixTTC = $prixTTC * $produit->getPromotion()->getTauxPromotion();
//
//            }
//            $prixTTC = number_format($prixTTC, 2, '.', '');
//
//            $oldPrice = $produit->getPrixHT() + ($produit->getPrixHT() * $produit->getTVA()->getTauxTva() / 100);
//            $oldPrice = number_format($oldPrice, 2, '.', '');
//            $photosProducts = $photoRepo->searchPhotoByProduit($produit);
//            $productsData[] = [
//                'produit' => $produit,
//                'prixTTC' => $prixTTC,
//                'oldPrice' => $oldPrice,
//                'photos' => $photosProducts,
//            ];
//        }

//        $productsDataNew = [];
//
//        foreach ($newsProducts as $newsProduct) {
//            $prixTTC = $newsProduct->getPrixHT() + ($newsProduct->getPrixHT() * $newsProduct->getTVA()->getTauxTva() / 100);
//
//            if ($newsProduct->getPromotion() !== null) {
//                $prixTTC = $prixTTC * $newsProduct->getPromotion()->getTauxPromotion();
//            }
//            $prixTTC = number_format($prixTTC, 2, '.', '');
//
//            $oldPrice = $newsProduct->getPrixHT() + ($newsProduct->getPrixHT() * $newsProduct->getTVA()->getTauxTva() / 100);
//            $oldPrice = number_format($oldPrice, 2, '.', '');
//            $photosProducts = $photoRepo->searchPhotoByProduit($produit);
//            $productsDataNew[] = [
//                'produit' => $newsProduct,
//                'prixTTC' => $prixTTC,
//                'oldPrice' => $oldPrice,
//                'photos' => $photosProducts,
//
//            ];
//
//        }
        $productData = $categorieService->getProducts($products, $photoRepo);
        $productNewData = $categorieService->getProducts($newsProducts, $photoRepo);

        return $this->render('categorie/produit_categorie.html.twig', [
            'produits' => $productData,
            'categorieParente' => $categorie_parente,
            'newsProducts' => $productNewData,
            'categorie' => $categorie,
            'photos' => $photos,
        ]);
    }

    public function list(CategorieRepository $cateRepo, Request $request): Response
    {
        $cate = $cateRepo->searchCategorieParente(
            $request->query->get('libelle', ''),

        );
        return $this->render('categorie/_categories.html.twig', [
            'cate' => $cate,
            'libelle' => $request->query->get('libelle', ''),
        ]);
    }
}
