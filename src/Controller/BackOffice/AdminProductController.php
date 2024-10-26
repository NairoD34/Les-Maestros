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

#[Route('admin/')]
class AdminProductController extends AbstractController
{

    #[Route('new_product', name: 'app_new_product')]
    public function new(
        Request                $request,
        Security               $security,
        PhotosRepository       $photo,
        ProductRepository      $productRepo,
        FormHandlerService     $formHandler
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $product = new Product();
        $formResult = $formHandler->handleProduct(false, $request, $product, $photo, $productRepo);

        if ($formResult) {
            return $this->redirectToRoute('app_product_list_admin');
        }
        return $this->render('BackOffice/Product/product_new.html.twig', [
            'title' => 'Création d\'un nouveau produit',
            'form' => $formResult->createView(),
        ]);
    }

    #[Route('product_list', name: 'app_product_list_admin')]
    public function list(
        AdminProductRepository $productRepo,
        Security               $security,
        Request                $request
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
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

    #[Route('product_show/{id}', name: 'app_product_show_admin')]
    public function showProducts(
        ?Product $product,
        Security $security,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('BackOffice/Product/product_show_admin.html.twig', [
            'title' => 'Fiche d\'un produit',
            'product' => $product,
        ]);
    }

    #[Route('update_product/{id}', name: 'app_update_product')]
    public function update(
        Request            $request,
        ?Product           $product,
        Security           $security,
        PhotosRepository   $photo,
        FormHandlerService $formHandler
    )
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        if (!$product) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $formResult = $formHandler->handleProduct(true, $request, $product, $photo, null);

        if ($formResult) {
            return $this->redirectToRoute('app_product_list_admin');
        }
        return $this->render('BackOffice/Product/product_new.html.twig', [
            'title' => 'Mise à jour d\'un produit',
            'form' => $formResult->createView(),
        ]);
    }

    #[Route('delete_product/{id}', name: 'app_delete_product', methods: ['POST'])]
    public function delete(
        ?Product               $product,
        Security               $security,
        EntityManagerInterface $em
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if (!$product) {
            return $this->redirectToRoute('app_admin_dashboard');
        }
        foreach ($product->getCartProduct() as $cartProduct) {
            $em->remove($cartProduct); // ou $em->detach($panierProduit);
        }

        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('app_product_list_admin');
    }


}
    
   

   
   
    
   


    
