<?php

namespace App\Controller\FrontOffice;

use App\Entity\Commande;
use App\Entity\LigneDeCommande;
use App\Form\CommandeFormType;
use App\Repository\AdresseRepository;
use App\Repository\EtatRepository;
use App\Repository\PanierRepository;
use App\Repository\PhotosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function NewCommande(Security $security, Request $request, EntityManagerInterface $em, EtatRepository $etatRepo, PanierRepository $panierRepo, PhotosRepository $photoRepo, AdresseRepository $adresseRepo): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }
        $user = $security->getUser();
        $id = $user->getId();
        $commande = new Commande();
        $adressesUtilisateur = $adresseRepo->findBy(['users' => $user]);
        $form = $this->createForm(CommandeFormType::class, $commande, [
            'adressesUtilisateur' => $adressesUtilisateur,
        ]);

        $panier = $panierRepo->getLastPanier($id);

        $total = 0;
        foreach ($panier->getPanierProduits() as $lignePanier) {

            $produits[] = [
                'id' => $lignePanier->getId(),
                'produit' => $lignePanier->getProduit(),
                'qte' => $lignePanier->getQuantite(),
                'photo' => $photoRepo->searchOnePhotoByProduit($lignePanier->getProduit()->getId()),
                'prixTTC' => $lignePanier->getProduit()->getPrixHT() + ($lignePanier->getProduit()->getPrixHT() * $lignePanier->getProduit()->getTVA()->getTauxTva() / 100),
            ];
            $total += ($lignePanier->getProduit()->getPrixHT() + ($lignePanier->getProduit()->getPrixHT() * $lignePanier->getProduit()->getTVA()->getTauxTva() / 100)) * $lignePanier->getQuantite();
            $total = number_format($total, 2, '.', '');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $commande->setLivraison($data->getLivraison());
            $commande->setPaiement($data->getPaiement());
            $etatUnique = $etatRepo->find(['id' => 1]);
            $commande->setEstFacture($data->getEstFacture());
            $commande->setEstLivre($data->getEstLivre());
            $commande->setEtat($etatUnique);
            $commande->setUsers($security->getUser());
            $commande->setPanier($panier);
            $commande->setDateCommande(new \DateTimeImmutable());
            $commande->setPrixTtcCommande($total);
            // Sauvegardez la commande en base de données
            foreach ($panier->getPanierProduits() as $lignePanier) {
                $ligneCommande = new LigneDeCommande();
                $ligneCommande->setCommande($commande); // Assurez-vous que votre entité LigneCommande a une méthode setCommande pour associer à la commande principale
                $ligneCommande->setNomProduit($lignePanier->getProduit()->getlibelle());
                $ligneCommande->setPrixProduit($lignePanier->getProduit()->getPrixHt());
                $ligneCommande->setTauxTva($lignePanier->getProduit()->getTVA()->getTauxTva());
                $ligneCommande->setNombreArticle($lignePanier->getQuantite());
                $ligneCommande->setPrixTotal($total);

                $utilisateur = $commande->getUsers();
                if ($utilisateur) {
                    $ligneCommande->setNomUtilisateur($utilisateur->getNom());
                    $ligneCommande->setPrenomUtilisateur($utilisateur->getPrenom());
                    $ligneCommande->setEmailUtilisateur($utilisateur->getEmail());
                }
                $em->persist($ligneCommande);
            }
            $em->persist($commande);

            $em->flush();
            $this->addFlash('success', 'Votre commande a bien été validée.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'form' => $form,
            'totalttc' => $total,
        ]);
    }


}
