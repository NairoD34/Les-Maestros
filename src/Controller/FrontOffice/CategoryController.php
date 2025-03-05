<?php

namespace App\Controller\FrontOffice;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProductRepository;
use App\Service\FrontOffice\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Contrôleur pour gérer les opérations liées aux catégories.
class CategoryController extends AbstractController
{

    //Affiche la liste de toutes les catégories.
    #[Route('/category', name: 'app_categorie')]
    public function index(CategoryRepository $cateRepo): Response
    {
        //Recherche toutes les catégories.
        $category = $cateRepo->findAll();
        return $this->render('FrontOffice/category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $category,
        ]);
    }

    //Affiche les détails d'une catégorie spécifique.
    #[Route('/main-category/{id}', name: 'app_categorie_show')]
    public function showParentCategories(
        ?Category          $category,
        CategoryRepository $cateRepo,
        PhotosRepository   $photoRepo
    )

    {
        if (!$category) {
            //Si la catégorie n'existe pas, on redirige vers la page d'accueil.
            return $this->redirectToRoute('app_index');
        }
        //Recherche la photo assocée à la catégorie.
        $photo = $photoRepo->searchPhotoByCategory($category);
        $parent = $cateRepo->searchParentCategory($category->getTitle());
        $children = $cateRepo->searchChildCategory($category);

        return $this->render('FrontOffice/category/showparent.html.twig', [
            'title' => 'Catégorie',
            'cate' => $category,
            'parents' => $parent,
            'enfants' => $children,
            'photos' => $photo
        ]);
    }

    //Affiche la liste de toutes les catégories enfants.
    #[Route('/child/{id}', name: 'app_categorie_show_enfant')]
    public function showCategories(?Category $category)
    {
        if (!$category) {
            //Si la catégorie n'existe pas, on redirige vers la page d'accueil.
            return $this->redirectToRoute('app_index');
        }


        return $this->render('FrontOffice/category/showenfant.html.twig', [
            'title' => 'Catégorie',
            'cate' => $category,

        ]);
    }

    //Affiche les produits d'une catégorie spécifique.
    #[Route('/category-products/{id}', name: 'app_produit_categorie')]
    public function displayProductsByCategory(
        Category           $category,
        ProductRepository  $productRepo,
        CategoryRepository $categoryRepo,
        PhotosRepository   $photoRepo,
        CategoryService    $categoryService
    ): Response
    {
        //Recherche des produits et photos pour la catégorie
        $categoryId = $category->getId();
        $category = $categoryRepo->find($categoryId);
        $categoryParent = $categoryRepo->findParentCategoryIdByChildId($categoryId);
        $products = $productRepo->findProductsByCategoryId($categoryId);
        $newProducts = $productRepo->searchNew();
        $photos = $photoRepo->searchPhotoByCategory($category);

        //Génère les données pour les produits
        $productData = $categoryService->getProducts($products, $photoRepo);
        $productNewData = $categoryService->getProducts($newProducts, $photoRepo);

        return $this->render('FrontOffice/category/produit_categorie.html.twig', [
            'produits' => $productData,
            'categorieParente' => $categoryParent,
            'newsProducts' => $productNewData,
            'categorie' => $category,
            'photos' => $photos,
        ]);
    }

    //Affiche la liste de toutes les catégories.
    public function list(CategoryRepository $cateRepo, Request $request): Response
    {
        //Recherche les catégories parents
        $cate = $cateRepo->searchParentCategory(
            $request->query->get('title', ''),

        );
        return $this->render('FrontOffice/category/_categories.html.twig', [
            'cate' => $cate,
            'libelle' => $request->query->get('title', ''),
        ]);
    }
}
