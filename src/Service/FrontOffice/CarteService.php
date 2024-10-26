<?php

namespace App\Controller\FrontOffice;
namespace App\Service\FrontOffice;
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
use Symfony\Component\Form\FormFactoryInterface;

class CarteService
{
    public function __construct(
        private EntityManagerInterface $em,
        private EtatRepository $etatRepo,
        private Security $security,
        private PhotosRepository $photoRepo,
        private AdresseRepository $adresseRepo,
        private FormFactoryInterface $formFactory,
        private PanierRepository $panierRepo,
        ) {
    }

    /**
     * Return array with specific user's id, command object and a form with user's adresses
     */
    public function GetUserData()
    {        
        $user = $this->security->getUser();
        $id = $user->getId();
        $commande = new Commande();
        $adressesUtilisateur = $this->adresseRepo->findBy(['users' => $user]);
        $form = $this->formFactory->create(CommandeFormType::class, $commande, [
            'adressesUtilisateur' => $adressesUtilisateur,
        ]);
        $panier = $this->panierRepo->getLastPanier($id);
        $result = [
            'form' => $form,
            'commande' => $commande,
            'id' => $id,
            'cart' => $panier,
        ];
        return $result;
    }

    /**
     * returns an array with cart's price and products datas
     */

    public function CalculPanier()
    {
        $panier = $this->GetUserData()['cart'];
        $total = 0;
        $produits = [];
        foreach ($panier->getPanierProduits() as $lignePanier) {

            $produits[] = [
                'id' => $lignePanier->getId(),
                'produit' => $lignePanier->getProduit(),
                'qte' => $lignePanier->getQuantite(),
                'photo' => $this->photoRepo->searchOnePhotoByProduit($lignePanier->getProduit()->getId()),
                'prixTTC' => $lignePanier->getProduit()->getPrixHT() + ($lignePanier->getProduit()->getPrixHT() * $lignePanier->getProduit()->getTVA()->getTauxTva() / 100),
            ];
            $total += ($lignePanier->getProduit()->getPrixHT() + ($lignePanier->getProduit()->getPrixHT() * $lignePanier->getProduit()->getTVA()->getTauxTva() / 100)) * $lignePanier->getQuantite();
            $total = number_format($total, 2, '.', '');
        }
        return [
            'total' => $total,
            'produits' => $produits,
        ];
    }

    public function FormCommandeValidation($request)
    {
        $panier = $this->GetUserData()['cart'];
        $commande = $this->GetUserData()['commande'];
        $form = $this->GetUserData()['form'];
        $total = $this->CalculPanier($panier)['total'];
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
                if ($utilisateur) {
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

