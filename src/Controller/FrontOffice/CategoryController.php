<?php

namespace App\Controller\FrontOffice;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProduitRepository;
use App\Service\FrontOffice\ProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    #[Route('/categorie', name: 'app_categorie')]
    public function index(CategorieRepository $caterepo): Response
    {
        $category = $caterepo->findAll();
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $category,
        ]);
    }

    #[Route('/maincategory/id={id}', name: 'app_categorie_show')]
    public function showCategorieParente(
        Categorie $cate, 
        CategorieRepository $cateRepo, 
        PhotosRepository $photoRepo
        )

    {
        if ($cate === null) {
            return $this->redirectToRoute('app_index');
        }
        $photo = $photoRepo->searchPhotoByCategorie($cate);

        $children = $cateRepo->searchCategorieEnfant($cate);

        return $this->render('categorie/showparent.html.twig', [
            'title' => 'Catégorie',
            'cate' => $cate,
            'enfants' => $children,
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
        Categorie           $category,
        ProduitRepository   $productRepo,
        CategorieRepository $categoryRepo,
        PhotosRepository    $photoRepo,
        ProductsService    $productsService
    ): Response
    {
        $categoryId = $category->getId();
        $category = $categoryRepo->find($categoryId);
        $category_parente = $categoryRepo->findParentCategoryIdByChildId($categoryId);
        $products = $productRepo->findProduitsByCategorieId($categoryId);
        $newProducts = $productRepo->searchNew();
        $photos = $photoRepo->searchPhotoByCategorie($category);

        $productData = $productsService->getProducts($products, $photoRepo);
        $productNewData = $productsService->getProducts($newProducts, $photoRepo);

        return $this->render('categorie/produit_categorie.html.twig', [
            'produits' => $productData,
            'categorieParente' => $category_parente,
            'newsProducts' => $productNewData,
            'categorie' => $category,
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
