<?php

namespace App\Controller\BackOffice;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\BackOffice\FormHandlerService;

// Contrôleur pour gérer les opérations liées aux catégories dans le back-office.
#[Route('admin/')]
class AdminCategoryController extends AbstractController
{
// Affiche les produits d'une catégorie spécifique.
    #[Route('category/{id}', name: 'app_product_categorie')]
    public function displayProductsByCategories(
        Category           $categories,
        ProductRepository  $productRepo,
        CategoryRepository $categoryRepo,
        PhotosRepository   $photoRepo,
        Security           $security
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $categoryId = $categories->getId();
        $category = $categoryRepo->find($categoryId);
        $products = $productRepo->findProductsByCategoryId($categoryId);
        $photo = $photoRepo->searchPhotoByCategory($categories);


        return $this->render('BackOffice/Category/product_categorie.html.twig', [
            'products' => $products,
            'category' => $category,
            'photos' => $photo,
        ]);
    }

// Affiche les détails d'une catégorie spécifique.
    #[Route('category_show/{id}', name: 'app_category_show_admin')]
    public function show(
        Category $category,
        Security $security,
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('BackOffice/Category/category_show.html.twig', [
            'title' => 'Fiche d\'une categorie',
            'category' => $category,
        ]);
    }

// Liste toutes les catégories, avec une option de recherche par nom.
    #[Route('category_list', name: 'app_category_list_admin')]
    public function list(
        CategoryRepository $categoryRepo,
        Security           $security,
        Request            $request
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        $categories = $categoryRepo->searchByName($request->query->get('title', ''));

        return $this->render('BackOffice/Category/category_list.html.twig', [
            'title' => 'Liste des catégories',
            'category' => $categories,
            'libelle' => $request->query->get('title', ''),
        ]);
    }

    // Crée une nouvelle catégorie.
// Gère le formulaire pour la création de la catégorie.
    #[Route('new_category', name: 'app_new_category')]
    public function new(
        Request            $request,
        Security           $security,
        PhotosRepository   $photo,
        CategoryRepository $categoryRepo,
        FormHandlerService $formHandler,

    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $category = new Category();
        $formResult = $formHandler->handleCategory(false, $request, $category, $photo, $categoryRepo);
        if ($formResult["validate"]) {
            return $this->redirectToRoute('app_category_list_admin');
        }
        return $this->render('BackOffice/Category/category_new.html.twig', [
            'title' => 'Création d\'une nouvelle catégorie',
            'form' => $formResult['form']->createView(),
        ]);
    }

// Met à jour une catégorie existante.
    #[Route('update_category/{id}', name: 'app_update_category')]
    public function update(
        Request            $request,
        ?Category          $category,
        Security           $security,
        CategoryRepository $categoryRepo,
        PhotosRepository   $photo,
        FormHandlerService $formHandler,

    )
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        if (!$category) {
            return $this->redirectToRoute('app_dashboard_admin');
        }

        $formResult = $formHandler->handleCategory(true, $request, $category, $photo, $categoryRepo);

        if ($formResult["validate"]) {
            return $this->redirectToRoute('app_category_list_admin');
        }

        return $this->render('BackOffice/Category/category_new.html.twig', [
            'title' => 'Mise à jour d\'une catégorie',
            'form' => $formResult["form"],
        ]);
    }

    // Supprime une catégorie existante.
// Vérifie si l'utilisateur a le rôle ADMIN avant de continuer.
// Détache les produits et les sous-catégories de la catégorie à supprimer.
    #[Route('delete_category/{id}', name: 'app_delete_category', methods: ['POST'])]
    public function delete(
        ?Category              $category,
        Security               $security,
        EntityManagerInterface $em
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        if (!$category) {
            return $this->redirectToRoute('app_dashboard_admin');
        }

        foreach ($category->getProducts() as $product) {
            $product->setCategory(null);
        }
        foreach ($category->getChildCategory() as $cateChild) {
            $cateChild->setParentCategory(null);
            $em->persist($cateChild);
        }
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('app_category_list_admin');
    }
}
