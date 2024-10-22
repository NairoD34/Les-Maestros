<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Form\AdminCommandeFormType;

#[Route('admin/')]
class AdminCommandeController extends AbstractController
{
    #[Route('commande', name: 'app_commande_admin')]
    public function index(Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    #[Route('commande_list', name: 'app_commande_list_admin')]
    public function list(
        CommandeRepository $commandeRepo,
        Security $security,
        ?Commande $commande,
        Request $request
    ): Response {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        $commande = $commandeRepo->searchByName($request->query->get('id', ''));
        if (empty($commande)) {
            return $this->render('admin/emptyCommande.html.twig');
        }
        return $this->render('admin/commande_list.html.twig', [
            'title' => 'Liste des commandes',
            'commande' => $commande,
            'id' => $request->query->get('id', ''),
        ]);
    }

    #[Route('commande_show/{id}', name: 'app_commande_show_admin')]
    public function showCommande(
        ?Commande $commande,
        Security $security,
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('admin/commande_show_admin.html.twig', [
            'title' => 'Fiche de la commande',
            'commande' => $commande,
        ]);
    }


    #[Route('update_commande/{id}', name: 'app_update_commande_admin')]
    public function update(
        Request $request,
        EntityManagerInterface $em,
        ?Commande $commande,
        Security $security
    ) {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if ($commande === null) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $form = $this->createForm(AdminCommandeFormType::class, $commande);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($commande);
            $em->flush();
            return $this->redirectToRoute('app_commande_list_admin');
        }
        return $this->render('admin/commande_update.html.twig', [
            'title' => 'Mise à jour de la commande',
            'form' => $form->createView(),
        ]);
    }
}
