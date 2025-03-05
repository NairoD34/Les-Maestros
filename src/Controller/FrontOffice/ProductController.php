<?php

namespace App\Controller\FrontOffice;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Service\FrontOffice\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Classe pour gérer les opérations liées aux produits.
class ProductController extends AbstractController
{
    //Affiche la liste des produits.
    #[Route('/products', name: 'app_produit')]
    public function index(ProductRepository $productRepo): Response
    {
        //Recherche des nouveaux produits
        $products = $productRepo->searchNew();
        return $this->render('FrontOffice/product/show.html.twig', [
            'controller_name' => 'ProductController',
            'produits' => $products,
            'title' => 'Liste des produits',
        ]);
    }

    //Affiche un produit.
    #[Route('/product/{id}', name: 'app_show_produit')]
    public function showProducts(
        ?Product       $product,
        ProductService $productService,
    ): Response
    {
        if (!$product) {
            //Redirige vers la home page si le produit n'existe pas.
            return $this->redirectToRoute('app_index');
        }

        //Affiche les détails d'un produit.
        $results = $productService->getDetailsAboutProduct($product);

        return $this->render('FrontOffice/product/show.html.twig',
            $results
        );
    }

    //Ajoute un produit au panier.
    #[Route('/add-product/{id}', name: "app_add_produit_to_Cart")]
    public function addToCart(
        Security               $security,
        ?Product               $product,
        CartRepository         $cartRepo,
        Request                $request,
        CartProductRepository  $cartProductRepo,
        EntityManagerInterface $em
    )
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        if (!$product) {
            // Redirige vers la home page si le produit n'existe pas.
            return $this->redirectToRoute('app_index');
        }

        //Recherche du panier.
        $user = $security->getUser();
        $cart = $cartRepo->getLastCartOrder($user->getId());

        if (!$cart) {
            //Si le panier n'existe pas, le cree.
            $cart = new Cart();
            $cart->setUsers($user);
            $em->persist($cart);
            $em->flush();
        }

        //Recherche du produit dans le panier.
        $idProduct = $product->getId();
        $idCart = $cart->getId();

        $productInCart = $cartProductRepo->getCartProductbyId($product, $cart);

        if ($this->isCsrfTokenValid('addToCart' . $product->getId(), $request->request->get('_token'))) {
            if (is_null($productInCart)) {
            //Si le produit n'existe pas dans le panier, l'ajoute.
                $cartProductRepo->AddProductToCartProduct($idProduct, $idCart, 1);
            } else {
            //Si le produit existe, ajoutez une quantité.
                $qte = $productInCart->getQuantity() + 1;
                $cartProductRepo->updateQuantityInCartProduct($qte, $idProduct, $idCart);
            }
            $this->addFlash('nice', 'Le produit a été ajouté au panier avec succès.');
            return $this->redirectToRoute('app_show_produit', ['id' => $idProduct], Response::HTTP_SEE_OTHER);
        }
    }
}
