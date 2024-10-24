<?php

namespace App\Controller;

use App\Entity\PanierProduit;
use App\Entity\Produit;
use App\Repository\PanierProduitRepository;
use App\Repository\PanierRepository;
use App\Repository\PhotosRepository;
use App\Service\FrontOffice\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(
        Security $security, 
        PanierService $panierService,
        ): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('panier/emptyPanier.html.twig');
        }
        
        $panier = $panierService->GetUserData()['cart'];
        if (!$panier) {
            return $this->render('panier/emptyPanier.html.twig');
        }
        $produits = $panierService->CalculPanier()['produits'];
        $total = $panierService->CalculPanier()['total'];
        if ($total === 0) {
            return $this->render('panier/emptyPanier.html.twig');
        }
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'produits' => $produits,
            'total' => $total,
        ]);
    }
    
    #[Route('delete_produit_panier/{id}', name: 'app_delete_produit_panier', methods: ['POST'])]
    public function delete(
        Request $request,
        PanierProduit $panierProduit,
        Security $security,
        PanierRepository $panierRepo,
        EntityManagerInterface $entityManager
    ): Response 
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        if ($panierProduit === null) {
            return $this->redirectToRoute('app_index');
        }
        if ($this->isCsrfTokenValid('delete' . $panierProduit->getId(), $request->request->get('_token'))) {
            if ($this->isCsrfTokenValid('delete' . $panierProduit->getId(), $request->request->get('_token'))) {

                $entityManager->remove($panierProduit);

                // Vérifiez si le panier est maintenant vide
                $panier = $panierRepo->findOneBy(['id' => $panierProduit->getPanier()->getId()]);

                if ($panier && $panier->getPanierProduits()->isEmpty()) {

                    $entityManager->remove($panier);
                }
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_panier', [], Response::HTTP_SEE_OTHER);
        }
    }
    #[Route('remove_produit_panier/{id}', name: 'app_remove_produit_panier', methods: ['POST'])]
    public function remove(
        Security $security,
        Produit $produit,
        PanierRepository $panierRepo,
        PanierProduitRepository $panierProduitRepo,
        EntityManagerInterface $em,
        Request $request,
    ) {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }
        if ($produit === null) {
            return $this->redirectToRoute('app_index');
        }
        $idProduit = $produit->getId();
        $Panier = $panierRepo->getLastPanierCommande($security->getUser()->getId());
        $idPanier = $Panier->getId();
        $produitInPanier = $panierProduitRepo->getPanierProduitbyId($produit, $Panier);
        if (!$produitInPanier) {
            // Si le produit n'est pas trouvé dans le panier, redirigez vers empty.html.twig
            return $this->render('panier/emptyPanier.html.twig');
        }
        if ($this->isCsrfTokenValid('removeToPanier' . $produit->getId(), $request->request->get('_token'))) {
            if ($this->isCsrfTokenValid('removeToPanier' . $produit->getId(), $request->request->get('_token'))) {
                $qte = $produitInPanier->getQuantite();
                if ($qte > 1) {
                    $qte--;
                    $panierProduitRepo->updateQuantitéInProduiPanier($qte, $idProduit, $idPanier);
                } else {
                    $em->remove($produitInPanier);
                    $em->flush();
                }
            }
            return $this->redirectToRoute('app_panier', [], Response::HTTP_SEE_OTHER);
        }
    }
    #[Route('add_qte_produit_panier/{id}', name: 'app_add_qte_produit_panier', methods: ['POST'])]
    public function addQuantite(
        Security $security,
        Produit $produit,
        PanierRepository $panierRepo,
        PanierProduitRepository $panierProduitRepo,
        Request $request,
    ) {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }
        if ($produit === null) {
            return $this->redirectToRoute('app_index');
        }
        $idProduit = $produit->getId();
        $Panier = $panierRepo->getLastPanierCommande($security->getUser()->getId());
        $idPanier = $Panier->getId();
        $produitInPanier = $panierProduitRepo->getPanierProduitbyId($produit, $Panier);
        if ($this->isCsrfTokenValid('addQteToPanier' . $produit->getId(), $request->request->get('_token'))) {
            if ($this->isCsrfTokenValid('addQteToPanier' . $produit->getId(), $request->request->get('_token'))) {
                $qte = $produitInPanier->getQuantite();
                $qte++;
                $panierProduitRepo->updateQuantitéInProduiPanier($qte, $idProduit, $idPanier);
            }
            return $this->redirectToRoute('app_panier', [], Response::HTTP_SEE_OTHER);
        }
    }
}
