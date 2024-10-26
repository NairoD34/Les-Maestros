<?php

namespace App\Controller\FrontOffice;

use App\Repository\CommandeRepository;
use App\Repository\LigneDeCommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGeneratorController extends AbstractController
{
    #[Route('/pdf/{id}', name: 'app_pdf_generator')]
    public function index(
        $id,
        LigneDeCommandeRepository $ligneRepo,
        CommandeRepository $commandeRepo,
    ): Response
    {
        $dataProduit = $ligneRepo->findByIdCommande($id);
        $dataCommande = $commandeRepo->findById($id);

        foreach ($dataCommande as $dataC) {
            $adresseLivraison = $dataC->getEstLivre();
            $adresseFacturation = $dataC->getEstFacture();
            $html = $this->renderView('pdf_generator/index.html.twig', [
                'dataProduit' => $dataProduit,
                'dataCommande' => $dataCommande,
                'adresseLivraison' => $adresseLivraison,
                'adresseFacturation' => $adresseFacturation,
                'logoUrl' =>  $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/img/logo.png'),
            ]);

            $options = new Options();
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfOutput = $dompdf->output();
        }

        return new Response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function imageToBase64($path)
    {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
