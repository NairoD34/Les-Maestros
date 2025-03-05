<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_paiement')]
    public function index(): Response
    {
        return $this->render('FrontOffice/payment/index.html.twig', [
            'controller_name' => 'PaiementController',
        ]);
    }
}
