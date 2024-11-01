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

class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(
        Security    $security,
        CartService $CartService,
    ): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('panier/emptyPanier.html.twig');
        }

        $panier = $CartService->GetUserData()['cart'];
        if (!$panier) {
            return $this->render('panier/emptyPanier.html.twig');
        }
        $products = $CartService->CalculCart()['Products'];
        $total = $CartService->CalculCart()['total'];
        if ($total === 0) {
            return $this->render('panier/emptyPanier.html.twig');
        }
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'Products' => $products,
            'total' => $total,
        ]);
    }

    #[Route('delete_Product_panier/{id}', name: 'app_delete_Product_panier', methods: ['POST'])]
    public function delete(
        Request                $request,
        ?CartProduct           $CartProduct,
        Security               $security,
        CartRepository         $cartRepo,
        EntityManagerInterface $entityManager
    ): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        if (!$CartProduct) {
            return $this->redirectToRoute('app_index');
        }

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

    #[Route('remove_Product_panier/{id}', name: 'app_remove_Product_panier', methods: ['POST'])]
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
            return $this->redirectToRoute('app_index');
        }

        if (!$product) {
            return $this->redirectToRoute('app_index');
        }

        $idProduct = $product->getId();
        $cart = $cartRepo->getLastCartOrder($security->getUser()->getId());
        $idPanier = $cart->getId();
        $productInPanier = $CartProductRepo->getCartProductbyId($product, $cart);

        if (!$productInPanier) {
            //Si le Product n'est pas trouvé dans le panier, redirigez vers empty.html.twig
            return $this->render('panier/emptyPanier.html.twig');
        }

        if ($this->isCsrfTokenValid('removeToPanier' . $product->getId(), $request->request->get('_token'))) {

            $qte = $productInPanier->getQuantity();
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

    #[Route('add_qte_Product_panier/{id}', name: 'app_add_qte_Product_panier', methods: ['POST'])]
    public function addQuantities(
        Security              $security,
        ?Product              $product,
        CartRepository        $cartRepo,
        CartProductRepository $CartProductRepo,
        Request               $request,
    )
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        if (!$product) {
            return $this->redirectToRoute('app_index');
        }

        $idProduct = $product->getId();
        $Panier = $cartRepo->getLastCartOrder($security->getUser()->getId());
        $idPanier = $Panier->getId();
        $productInPanier = $CartProductRepo->getCartProductbyId($product, $Panier);

        if ($this->isCsrfTokenValid('addQteToPanier' . $product->getId(), $request->request->get('_token'))) {
            $qte = $productInPanier->getQuantity();
            $qte++;
            $CartProductRepo->updateQuantityInCartProduct($qte, $idProduct, $idPanier);
            return $this->redirectToRoute('app_panier', [], Response::HTTP_SEE_OTHER);
        }
    }
}