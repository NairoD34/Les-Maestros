<?php

namespace App\Controller\BackOffice;

use App\Entity\Product;
use App\Repository\AdminProductRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PhotosRepository;
use App\Service\BackOffice\FormHandlerService;

// Contrôleur pour gérer les opérations liées aux produits dans le back-office.
#[Route('admin/')]
class AdminProductController extends AbstractController
{

    // Crée un nouveau produit.
    #[Route('new_product', name: 'app_new_product')]
    public function new(
        Request            $request,
        Security           $security,
        PhotosRepository   $photo,
        ProductRepository  $productRepo,
        FormHandlerService $formHandler
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        // Instancie un nouveau produit.
        $product = new Product();
        $formResult = $formHandler->handleProduct(false, $request, $product, $photo, $productRepo);

        if ($formResult['condition']) {
            // Redirige vers la liste si le produit a été créé.
            return $this->redirectToRoute('app_product_list_admin');
        }

        // Rend la vue avec les données du formulaire.
        return $this->render('BackOffice/Product/product_new.html.twig', [
            'title' => 'Création d\'un nouveau produit',
            'form' => $formResult['form']->createView(),
        ]);
    }

    // Liste tous les produits.
    #[Route('product_list', name: 'app_product_list_admin')]
    public function list(
        AdminProductRepository $productRepo,
        Security               $security,
        Request                $request
    ): Response {

        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        $products = $productRepo->findAll();
        $title = $productRepo->searchByName($request->query->get('title', ''));

        return $this->render('BackOffice/Product/product_list.html.twig', [
            'title' => 'Liste des produits',
            'product' => $products,
            'libelle' => $title,
        ]);
    }

    // Affiche les détails d'un produit spécifique.
    #[Route('product_show/{id}', name: 'app_product_show_admin')]
    public function showProducts(
        ?Product $product,
        Security $security,
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        if (!$product) {
            // Redirige vers la liste si le produit n'existe pas.
            return $this->redirectToRoute('app_product_list_admin');
        }

        return $this->render('BackOffice/Product/product_show_admin.html.twig', [
            'title' => 'Fiche d\'un produit',
            'product' => $product,
        ]);
    }

    // Mise à jour d'un produit existant.
    #[Route('update_product/{id}', name: 'app_update_product')]
    public function update(
        Request            $request,
        ?Product           $product,
        Security           $security,
        PhotosRepository   $photo,
        FormHandlerService $formHandler,
        ProductRepository  $productRepo,
    ) {
        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        if (!$product) {
            // Redirige vers la liste si le produit n'existe pas.
            return $this->redirectToRoute('app_product_list_admin');
        }

        $formResult = $formHandler->handleProduct(true, $request, $product, $photo, $productRepo);
        if ($formResult['condition']) {
            // Redirige vers la liste si le produit a été mis à jour.

            return $this->redirectToRoute('app_product_list_admin');
        }
        return $this->render('BackOffice/Product/product_new.html.twig', [
            'title' => 'Mise à jour d\'un produit',
            'form' => $formResult['form']->createView(),
        ]);
    }

    // Suppression d'un produit.
    #[Route('delete_product/{id}', name: 'app_delete_product', methods: ['POST'])]
    public function delete(
        ?Product               $product,
        Security               $security,
        EntityManagerInterface $em
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }
        if (!$product) {
            // Redirige vers la liste si le produit n'existe pas.
            return $this->redirectToRoute('app_product_list_admin');
        }

        // Détachement des produits du panier.
        foreach ($product->getCartProduct() as $cartProduct) {
            $em->remove($cartProduct); // ou $em->detach($panierProduit);
        }

        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('app_product_list_admin');
    }
}
