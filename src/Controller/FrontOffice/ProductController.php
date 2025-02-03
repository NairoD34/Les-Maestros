<?php

namespace App\Controller\FrontOffice;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProductRepository;
use App\Service\FrontOffice\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_produit')]
    public function index(ProductRepository $productRepo): Response
    {
        $products = $productRepo->searchNew();
        return $this->render('FrontOffice/product/index.html.twig', [
            'controller_name' => 'ProductController',
            'produits' => $products
        ]);
    }

    #[Route('/product/{id}', name: 'app_show_produit')]
    public function showProducts(
        ?Product       $product,
        ProductService $productService,
    ): Response
    {
        if (!$product) {
            return $this->redirectToRoute('app_produit');
        }

        $results = $productService->getDetailsAboutProduct($product);

        return $this->render('FrontOffice/product/show.html.twig',
            $results
        );
    }

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
            return $this->redirectToRoute('app_index');
        }

        if (!$product) {
            return $this->redirectToRoute('app_produit');
        }

        $user = $security->getUser();
        $cart = $cartRepo->getLastCartOrder($user->getId());

        if (!$cart) {
            $cart = new Cart();
            $cart->setUsers($user);
            $em->persist($cart);
            $em->flush();
        }

        $idProduct = $product->getId();
        $idCart = $cart->getId();

        $productInCart = $cartProductRepo->getCartProductbyId($product, $cart);

        if ($this->isCsrfTokenValid('addToCart' . $product->getId(), $request->request->get('_token'))) {
            if (is_null($productInCart)) {
                $cartProductRepo->AddProductToCartProduct($idProduct, $idCart, 1);
            } else {
                $qte = $productInCart->getQuantity() + 1;
                $cartProductRepo->updateQuantityInCartProduct($qte, $idProduct, $idCart);
            }
            $this->addFlash('nice', 'Le produit a été ajouté au panier avec succès.');
            return $this->redirectToRoute('app_show_produit', ['id' => $idProduct], Response::HTTP_SEE_OTHER);
        }
    }
}
