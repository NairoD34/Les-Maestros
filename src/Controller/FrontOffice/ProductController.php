<?php

namespace App\Controller\FrontOffice;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_produit')]
    public function index(ProductRepository $productRepo): Response
    {
        $products = $productRepo->searchNew();
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProductController',
            'produits' => $products
        ]);
    }

    #[Route('/product/{id}', name: 'app_show_produit')]
    public function showProducts(
        PhotosRepository   $photoRepo,
        ?Product           $product,
        CategoryRepository $categoryRepo
    ): Response
    {

        if (!$product) {
            return $this->redirectToRoute('app_produit');
        }

        $priceTTC = $product->getTaxFreePrice() + ($product->getTaxFreePrice() * $product->getTaxRate()->getTaxRate() / 100);

        $priceTTCFormatted = number_format($priceTTC, 2, '.', '');


        if ($product->getSales()) {
            $newTtcPrice = $priceTTCFormatted * $product->getSales()->getSalesRate();
            $newTtcPriceFormatted = number_format($newTtcPrice, 2, '.', '');
        }

        $oldPrice = $product->getTaxFreePrice() + ($product->getTaxFreePrice() * $product->getTaxRate()->getTaxRate() / 100);
        $oldPriceFormatted = number_format($oldPrice, 2, '.', '');

        $photos = $photoRepo->searchPhotoByProduct($product);
        $category = $product->getCategory()->getId();
        
        //Récupérer l'id de la catégorie parente pour le fil d'arrianne
        $parentCategory = $categoryRepo->findParentCategoryIdByChildId($category);
        return $this->render('produit/show.html.twig', [
            'title' => 'Fiche d\'un produit',
            'categorieParente' => $parentCategory,
            'categorie' => $category,
            'produit' => $product,
            'prixTTC' => $product->getSales() ? $newTtcPriceFormatted : $priceTTCFormatted,
            'photos' => $photos,
            'oldPrice' => $oldPriceFormatted,
        ]);
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
            $this->addFlash('nice', 'Le produit a été ajouté au Cart avec succès.');
            return $this->redirectToRoute('app_show_produit', ['id' => $idProduct], Response::HTTP_SEE_OTHER);
        }
    }
}
