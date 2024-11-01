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

class CategoryController extends AbstractController
{

    #[Route('/categorie', name: 'app_categorie')]
    public function index(CategoryRepository $cateRepo): Response
    {
        $category = $cateRepo->findAll();
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $category,
        ]);
    }

    #[Route('/maincategory/{id}', name: 'app_categorie_show')]
    public function showParentCategories(
        ?Category          $category,
        CategoryRepository $cateRepo,
        PhotosRepository   $photoRepo
    )

    {
        if (!$category) {
            return $this->redirectToRoute('app_index');
        }
        $photo = $photoRepo->searchPhotoByCategory($category);
        $parent = $cateRepo->searchParentCategory($category->getTitle());
        $children = $cateRepo->searchChildCategory($category);

        return $this->render('categorie/showparent.html.twig', [
            'title' => 'Catégorie',
            'cate' => $category,
            'parents' => $parent,
            'enfants' => $children,
            'photos' => $photo
        ]);
    }

    #[Route('/enfant/{id}', name: 'app_categorie_show_enfant')]
    public function showCategories(?Category $category)
    {
        if (!$category) {
            return $this->redirectToRoute('app_index');
        }


        return $this->render('categorie/showenfant.html.twig', [
            'title' => 'Catégorie',
            'cate' => $category,

        ]);
    }

    #[Route('/produit_categorie/{id}', name: 'app_produit_categorie')]
    public function displayProductsByCategory(
        Category           $category,
        ProductRepository  $productRepo,
        CategoryRepository $categoryRepo,
        PhotosRepository   $photoRepo,
        CategoryService    $categoryService
    ): Response
    {
        $categoryId = $category->getId();
        $category = $categoryRepo->find($categoryId);
        $categoryParent = $categoryRepo->findParentCategoryIdByChildId($categoryId);
        $products = $productRepo->findProductsByCategoryId($categoryId);
        $newProducts = $productRepo->searchNew();
        $photos = $photoRepo->searchPhotoByCategory($category);

        $productData = $categoryService->getProducts($products, $photoRepo);
        $productNewData = $categoryService->getProducts($newProducts, $photoRepo);

        return $this->render('categorie/produit_categorie.html.twig', [
            'produits' => $productData,
            'categorieParente' => $categoryParent,
            'newsProducts' => $productNewData,
            'categorie' => $category,
            'photos' => $photos,
        ]);
    }

    public function list(CategoryRepository $cateRepo, Request $request): Response
    {
        $cate = $cateRepo->searchParentCategory(
            $request->query->get('title', ''),

        );
        return $this->render('categorie/_categories.html.twig', [
            'cate' => $cate,
            'libelle' => $request->query->get('title', ''),
        ]);
    }
}
