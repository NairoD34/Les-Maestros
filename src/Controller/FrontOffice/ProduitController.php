<?php

namespace App\Controller\FrontOffice;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\PanierProduitRepository;
use App\Repository\PanierRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(ProduitRepository $produitRepo): Response
    {
        $produits = $produitRepo->searchNew();
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produits' => $produits
        ]);
    }

    #[Route('/produit/{id}', name: 'app_show_produit')]
    public function showProducts(PhotosRepository $photoRepo, ?Produit $produit,
    CategorieRepository $categorieRepo): Response
    {
    
        if ($produit === null) {
            return $this->redirectToRoute('app_produit');
        }
    

        $prixTTC = $produit->getPrixHT() + ($produit->getPrixHT() * $produit->getTVA()->getTauxTva() / 100);
        $prixTTC = number_format($prixTTC, 2, '.', '');
        // Vérifiez si le produit a une promotion
        if ($produit->getPromotion() !== null) {
            $prixTTC = $prixTTC * $produit->getPromotion()->getTauxPromotion();
            $prixTTC = number_format($prixTTC, 2, '.', '');
        }
        $oldPrice = $produit->getPrixHT() + ($produit->getPrixHT() * $produit->getTVA()->getTauxTva() / 100);
        $oldPrice = number_format($oldPrice, 2, '.', '');

        $photos = $photoRepo->searchPhotoByProduit($produit);
        $categorie = $produit->getCategorie()->getId();
        //Récupérer l'id de la catégorie parente pour le fil d'arrianne
        $categorie_parente= $categorieRepo->findParentCategoryIdByChildId($categorie);
        return $this->render('produit/show.html.twig', [
            'title' => 'Fiche d\'un produit',
            'categorieParente'=> $categorie_parente,
            'categorie' => $categorie,
            'produit' => $produit,
            'prixTTC' => $prixTTC,
            'photos' => $photos,
            'oldPrice' => $oldPrice,
        ]);
    }

    #[Route('/addproduit/{id}', name: "app_add_produit_to_panier")]
    public function addToPanier(
        Security $security,
        Produit $produit,
        PanierRepository $panierRepo,
        Request $request,
        PanierProduitRepository $panierProduitRepo,
        EntityManagerInterface $em
    ) {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        if ($produit === null) {
            return $this->redirectToRoute('app_produit');
        }

        $user = $security->getUser();
        $panier = $panierRepo->getLastPanierCommande($user->getId());

        if (!$panier) {
            $panier = new Panier();
            $panier->setUsers($user);
            $em->persist($panier);
            $em->flush();  // Enregistrer le panier en base de données
        }

        $idProduit = $produit->getId();
        $idPanier = $panier->getId();

        $produitInPanier = $panierProduitRepo->getPanierProduitbyId($produit, $panier);

        if ($this->isCsrfTokenValid('addToPanier' . $produit->getId(), $request->request->get('_token'))) {
            if (is_null($produitInPanier)) {
                $panierProduitRepo->AddProduitToPanierProduit($idProduit, $idPanier, 1);
            } else {
                $qte = $produitInPanier->getQuantite() + 1;
                $panierProduitRepo->updateQuantitéInProduiPanier($qte, $idProduit, $idPanier);
            }
            $this->addFlash('nice', 'Le produit a été ajouté au panier avec succès.');
            return $this->redirectToRoute('app_show_produit', ['id' => $idProduit], Response::HTTP_SEE_OTHER);
        }
    }
}
