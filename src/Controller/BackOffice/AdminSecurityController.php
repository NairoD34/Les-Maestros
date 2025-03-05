<?php

namespace App\Controller\BackOffice;

use App\Service\BackOffice\KpiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\Security;

// Classe pour gérer l'accès des utilisateurs au back-office.
#[Route(path: 'admin/')]
class AdminSecurityController extends AbstractController
{
    // Affiche la page de dashboard.
    #[Route(path: 'dashboard', name: 'app_admin_dashboard')]
    public function dashboard(Security $security, KpiService $kpi): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers la page d'accueil si l'utilisateur n'a pas les droits.
            return $this->redirectToRoute('app_index');
        }

        // Recupere les données des KPI.
        $data = $kpi->getAllData();
        return $this->render('BackOffice/dashboard.html.twig' ,[
            "data" => $data
        ]);
    }

}
