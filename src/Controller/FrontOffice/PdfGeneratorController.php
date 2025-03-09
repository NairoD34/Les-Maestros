<?php

namespace App\Controller\FrontOffice;

use App\Repository\OrderRepository;
use App\Repository\OrderLineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

// Classe pour gérer la generation de PDF.
class PdfGeneratorController extends AbstractController
{
    // Route pour la generation de PDF.
    #[Route('/pdf/{id}', name: 'app_pdf_generator')]
    public function index(
        int                 $id,
        OrderLineRepository $orderLineRepo,
        OrderRepository     $orderRepo
    ): Response
    {
        // Recupere les produits de la commande.
        $dataProducts = $orderLineRepo->findByOrderId($id);
        $orderData = $orderRepo->find($id);

        if (!$orderData) {
            // Redirige vers la liste si le message n'existe pas.
            throw $this->createNotFoundException('Commande non trouvée.');
        }

        // Recupere l'adresse de livraison et de facturation.
        $deliveryAddress = $orderData->getDelivered();
        $deliveryBill = $orderData->getBilled();

        // Genere le PDF.
        $html = $this->renderView('BackOffice/pdf_generator/index.html.twig', [
            'dataProduit' => $dataProducts,
            'dataCommande' => $orderData,
            'deliveryAddress' => $deliveryAddress,
            'deliveryBill' => $deliveryBill,
            'logoUrl' => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/build/images/logo.png'),
        ]);

        // Configuration du PDF.
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        // Generation du PDF.
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Generation du PDF.
        $pdfOutput = $dompdf->output();

        return new Response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }


    // Fonction pour convertir une image en Base64.
    private function imageToBase64($path)
    {
        // Recupere le type de l'image.
        $type = pathinfo($path, PATHINFO_EXTENSION);

        // Recupere les données de l'image.
        $data = file_get_contents($path);

        // Convertit l'image en Base64.
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
