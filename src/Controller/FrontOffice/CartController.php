<?php

namespace App\Controller\FrontOffice;

use App\Entity\CartProduct;
use App\Entity\Product;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Service\FrontOffice\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Contrôleur pour gérer le panier d'un utilisateur.
class CartController extends AbstractController
{
    //Affiche le panier si il existe, sinon affiche un message d'erreur.
    #[Route('/cart', name: 'app_panier')]
    public function index(
        Security    $security,
        CartService $CartService,
    ): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        // Recherche du panier de l'utilisateur.
        $panier = $CartService->GetUserData()['cart'];
        if (!$panier) {
            // Affiche un message d'erreur si le panier n'existe pas.
            return $this->render('FrontOffice/cart/emptyPanier.html.twig');
        }

        // Calcul des produits et du total.
        $products = $CartService->CalculCart()['products'];
        $total = $CartService->CalculCart()['total'];
        if ($total === 0) {
            // Affiche un message d'erreur si le panier est vide.
            return $this->render('FrontOffice/cart/emptyPanier.html.twig');
        }
        return $this->render('FrontOffice/cart/index.html.twig', [
            'controller_name' => 'PanierController',
            'products' => $products,
            'total' => $total,
        ]);
    }

    // Supprime un produit du panier.
    #[Route('delete_products_cart/{id}', name: 'app_delete_product_cart', methods: ['POST'])]
    public function delete(
        Request                $request,
        ?CartProduct           $CartProduct,
        Security               $security,
        CartRepository         $cartRepo,
        EntityManagerInterface $entityManager
    ): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        if (!$CartProduct) {
            // Redirige vers la page d'accueil si le produit n'existe pas.
            return $this->redirectToRoute('app_index');
        }

        // Supprime le produit du panier.
        if ($this->isCsrfTokenValid('delete' . $CartProduct->getId(), $request->request->get('_token'))) {
            $entityManager->remove($CartProduct);
            $panier = $cartRepo->findOneBy(['id' => $CartProduct->getCart()->getId()]);

            if ($panier && $panier->getCartProducts()->isEmpty()) {
                $entityManager->remove($panier);
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier', [], Response::HTTP_SEE_OTHER);

    }

    // Réduit la quantité d'un produit du panier.
    #[Route('remove_products_cart/{id}', name: 'app_remove_product_cart', methods: ['POST'])]
    public function remove(
        Security               $security,
        ?Product               $product,
        CartRepository         $cartRepo,
        CartProductRepository  $CartProductRepo,
        EntityManagerInterface $em,
        Request                $request,
    )
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        if (!$product) {
            // Redirige vers la page d'accueil si le produit n'existe pas.
            return $this->redirectToRoute('app_index');
        }

        // Recherche du produit dans le panier.
        $idProduct = $product->getId();
        $cart = $cartRepo->getLastCartOrder($security->getUser()->getId());
        $idPanier = $cart->getId();
        $productInPanier = $CartProductRepo->getCartProductbyId($product, $cart);

        if (!$productInPanier) {
            //Si le Product n'est pas trouvé dans le panier, redirigez vers empty.html.twig
            return $this->render('FrontOffice/cart/emptyPanier.html.twig');
        }

        // Supprime le produit du panier.
        if ($this->isCsrfTokenValid('removeToCart' . $product->getId(), $request->request->get('_token'))) {

            $qte = $productInPanier->getQuantity();
            // Si la quantité est supérieure à 1, diminue de 1, sinon supprime le produit du panier.
            if ($qte > 1) {
                $qte--;
                $CartProductRepo->updateQuantityInCartProduct($qte, $idProduct, $idPanier);
            } else {
                $em->remove($productInPanier);
                $em->flush();
            }
        }
        return $this->redirectToRoute('app_panier', [], Response::HTTP_SEE_OTHER);

    }

    // Ajoute la quantité d'un produit au panier.
    #[Route('add_qte_product_cart/{id}', name: 'app_add_qte_product_cart', methods: ['POST'])]
    public function addQuantities(
        Security              $security,
        ?Product              $product,
        CartRepository        $cartRepo,
        CartProductRepository $CartProductRepo,
        Request               $request,
    )
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        if (!$product) {
            // Redirige vers la page d'accueil si le produit n'existe pas.
            return $this->redirectToRoute('app_index');
        }

        // Recherche du produit dans le panier.
        $idProduct = $product->getId();
        $Panier = $cartRepo->getLastCartOrder($security->getUser()->getId());
        $idPanier = $Panier->getId();
        $productInPanier = $CartProductRepo->getCartProductbyId($product, $Panier);
        

        if (!$productInPanier) {
            // Redirige vers la page d'accueil si le produit n'existe pas dans le panier.
            return $this->redirectToRoute('app_index');
        }

        // Ajoute la quantité du produit au panier.
        if ($this->isCsrfTokenValid('addQteToCart' . $product->getId(), $request->request->get('_token'))) {
            $qte = $productInPanier->getQuantity();
            $qte++;
            $CartProductRepo->updateQuantityInCartProduct($qte, $idProduct, $idPanier);
            return $this->redirectToRoute('app_panier', [], Response::HTTP_SEE_OTHER);
        }
    }
}