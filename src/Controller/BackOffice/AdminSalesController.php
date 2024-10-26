<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Sales;
use App\Repository\SalesRepository;
use App\Service\BackOffice\FormHandlerService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('admin/')]
class AdminSalesController extends AbstractController
{

    #[Route('sales_show/{id}', name: 'app_sales_show')]
    public function showSales(
        ?Sales   $sales,
        Security $security,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('BackOffice/Sales/sales_show.html.twig', [
            'title' => 'Fiche d\'une promotion',
            'sales' => $sales,
        ]);
    }

    #[Route('sales_list', name: 'app_sales_list')]
    public function list(
        SalesRepository $salesRepo,
        Security        $security,
        Request         $request
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        $sales = $salesRepo->findAll();
        $salesByName = $salesRepo->searchByName($request->query->get('title', ''));

        return $this->render('BackOffice/Sales/sales_list.html.twig', [
            'title' => 'Liste des promotions',
            'sales' => $sales,
            'libelle' => $salesByName,
        ]);
    }

    #[Route('new_sales', name: 'app_new_sales')]
    public function new(
        Request            $request,
        Security           $security,
        FormHandlerService $formHandler
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $sales = new Sales();
        $formResult = $formHandler->handleSales($request, $sales);

        if ($formResult) {
            return $this->redirectToRoute('app_sales_list');
        }
        return $this->render('BackOffice/Sales/sales_new.html.twig', [
            'title' => 'Création d\'une nouvelle promotion',
            'form' => $formResult->createView(),
        ]);
    }

    #[Route('update_sales/{id}', name: 'app_update_sales')]
    public function update(
        Request            $request,
        ?Sales             $sales,
        Security           $security,
        FormHandlerService $formHandler
    )
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if (!$sales) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $formResult = $formHandler->handleSales($request, $sales);

        if ($formResult) {
            return $this->redirectToRoute('app_sales_list');
        }

        return $this->render('BackOffice/Sales/sales_new.html.twig', [
            'title' => 'Mise à jour d\'une promotion',
            'form' => $formResult,
        ]);
    }


    #[Route('delete_sales/{id}', name: 'app_delete_sales', methods: ['POST'])]
    public function delete(
        Request                $request,
        ?Sales                 $sales,
        Security               $security,
        EntityManagerInterface $entityManager
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if (!$sales) {
            return $this->redirectToRoute('app_admin_dashboard');
        }
        $products = $sales->getProduct();
        foreach ($products as $product) {
            $product->setSales(null);
            $entityManager->persist($product);
        }
        if ($this->isCsrfTokenValid('delete' . $sales->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sales);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sales_list', [], Response::HTTP_SEE_OTHER);
    }
}
