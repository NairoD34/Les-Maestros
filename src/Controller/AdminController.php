<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminFormType;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('admin/')]
class AdminController extends AbstractController
{
    #[Route('list_admin', name: 'app_list_admin')]
    public function list(AdminRepository $adminRepo, Request $request): Response
    {
        $trinom = $request->query->get('trinom', 'asc');
        $triprenom = $request->query->get('triprenom', 'asc');
        $admin = $adminRepo->searchByName($request->query->get('nom', ''), $trinom, $triprenom);

        return $this->render('admin/list.html.twig', [
            'title' => 'Liste des administrateurs',
            'administrateur' => $admin,
            'trinom' => $trinom,
            'triprenom' => $triprenom,
            'nom' => $request->query->get('nom', ''),
        ]);
    }

    #[Route('show/{id}', name: 'app_show_admin')]
    public function show(?Admin $admin): Response
    {
        if ($admin === null) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/show.html.twig', [
            'title' => 'Fiche d\'un administrateur',
            'administrateur' => $admin,
        ]);
    }

    #[Route('new', name: 'app_new_admin')]
    public function new(
        Request                     $request,
        EntityManagerInterface      $em,
        Security                    $security,
        UserPasswordHasherInterface $adminPasswordHasher
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $admin = new Admin();
        $form = $this->createForm(AdminFormType::class, $admin);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedRoles = $form->get('roles')->getData();
            $admin->setRoles($selectedRoles);
            $admin->setPassword(
                $adminPasswordHasher->hashPassword(
                    $admin,
                    $form->get('plainPassword')->getData()
                )
            );
            $em->persist($admin);
            $em->flush();
            return $this->redirectToRoute('app_list_admin');
        }
        return $this->render('admin/new.html.twig', [
            'title' => 'Création d\'un nouvel administrateur',
            'form' => $form->createView(),
        ]);
    }

    #[Route('update/{id}', name: 'app_update_admin')]
    public function update(
        Request                $request,
        EntityManagerInterface $em,
        ?Admin                 $admin,
        Security               $security
    )
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if ($admin === null) {
            return $this->redirectToRoute('app_list_admin');
        }

        $form = $this->createForm(AdminFormType::class, $admin);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($admin);
            $em->flush();
            return $this->redirectToRoute('app_list_admin');
        }
        return $this->render('admin/new.html.twig', [
            'title' => 'Mise à jour d\'un administrateur',
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_delete_admin', methods: ['POST'])]
    public function delete(
        Request                $request,
        Admin                  $admin,
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $admin->getId(), $request->request->get('_token'))) {
            $entityManager->remove($admin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_list_admin', [], Response::HTTP_SEE_OTHER);
    }
}
