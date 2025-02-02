<?php

namespace App\Controller\BackOffice;

use App\Service\BackOffice\KpiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\Security;

#[Route(path: 'admin/')]
class AdminSecurityController extends AbstractController
{
    #[Route(path: 'dashboard', name: 'app_admin_dashboard')]
    public function dashboard(Security $security, KpiService $kpi): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        $data = $kpi->getAllData();
        return $this->render('BackOffice/dashboard.html.twig' ,[
            "data" => $data
        ]);
    }

}
