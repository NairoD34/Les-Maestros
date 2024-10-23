<?php

namespace App\Service\FrontOffice\CommandeService;

use App\Entity\LigneDeCommande;
use App\Repository\EtatRepository;
use App\Repository\PhotosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CommandeService
{

    public function __construct(
        private EntityManagerInterface $em,
        private EtatRepository $etatRepo,
        private Security $security,
        private PhotosRepository $photoRepo,
        ) {
    }

    public function CalculTotalPanier($panier)
    {
        $total = 0;
        foreach ($panier->getPanierProduits() as $lignePanier) {

            $produits[] = [
                'id' => $lignePanier->getId(),
                'produit' => $lignePanier->getProduit(),
                'qte' => $lignePanier->getQuantite(),
                'photo' => $this->photoRepo->searchOnePhotoByProduit($lignePanier->getProduit()->getId()),
                'prixTTC' => $lignePanier->getProduit()->getPrixHT() + ($lignePanier->getProduit()->getPrixHT() * $lignePanier->getProduit()->getTVA()->getTauxTva() / 100),
            ];
            $total += ($lignePanier->getProduit()->getPrixHT() + ($lignePanier->getProduit()->getPrixHT() * $lignePanier->getProduit()->getTVA()->getTauxTva() / 100)) * $lignePanier->getQuantite();
            $total = number_format($total,2,'.','');
        }
        return $total;
    }

    public function FormCommandeValidation($form, $commande, $panier, $request)
    {
        $total = $this->CalculTotalPanier($panier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $commande->setLivraison($data->getLivraison());
            $commande->setPaiement($data->getPaiement());
            $etatUnique = $this->etatRepo->find(['id' => 1]);
            $commande->setEstFacture($data->getEstFacture());
            $commande->setEstLivre($data->getEstLivre());
            $commande->setEtat($etatUnique);
            $commande->setUsers($this->security->getUser());
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
                if($utilisateur){
                    $ligneCommande->setNomUtilisateur($utilisateur->getNom());
                    $ligneCommande->setPrenomUtilisateur($utilisateur->getPrenom());
                    $ligneCommande->setEmailUtilisateur($utilisateur->getEmail());
                }
                $this->em->persist($ligneCommande);
            }
            $this->em->persist($commande);

            $this->em->flush();
            return true;
        }
        return false;
    }
}