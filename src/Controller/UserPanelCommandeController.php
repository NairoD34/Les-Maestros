<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\CodePostal;
use App\Entity\Commande;
use App\Entity\Region;
use App\Entity\Users;
use App\Form\AdresseFormType;
use App\Form\UserFormType;
use App\Repository\AdresseRepository;
use App\Repository\CodePostalRepository;
use App\Repository\CommandeRepository;
use App\Repository\DepartementRepository;
use App\Repository\RegionRepository;
use App\Repository\UsersRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;



class UserPanelCommandeController extends AbstractController
{
    #[Route('/user/commande_list', name: 'app_commande_list')]
    public function list(
        CommandeRepository $commandeRepo,
        Security $security,
        ?Commande $commande,
        Request $request
    ): Response {

        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        $user = $this->getUser();
        $commandes = $commandeRepo->findBy(['users' => $user->getId()]);

        if (empty($commandes)) {
            return $this->render('user/emptyCommande.html.twig');
        }
        
        return $this->render('user/commande_list.html.twig', [
            'title' => 'Liste des commandes',
            'commande' => $commandes,
            'id' => $request->query->get('id', ''),
        ]);
    }

    #[Route('/user/commande_show/{id}', name: 'app_commande_show')]
    public function showCommande(
        ?Commande $commande,
        Security $security,
    ): Response {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }
        return $this->render('user/commande_show.html.twig', [
            'title' => 'Fiche de la commande',
            'commande' => $commande,
        ]);
    }
}
