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
    #[Route(path: 'login', name: 'app_admin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('app_admin_dashboard');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'username' => $lastUsername,
            'error' => $error,
            'action' => '{{ path=\'app_dashboard_admin\' }}',
        ]);
    }

    #[Route(path: 'logout', name: 'app_admin_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

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
