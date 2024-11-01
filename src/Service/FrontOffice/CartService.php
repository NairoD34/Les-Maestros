<?php

namespace App\Controller\FrontOffice;

namespace App\Service\FrontOffice;

use App\Entity\Orders;
use App\Entity\OrderLine;
use App\Form\CommandeFormType;
use App\Repository\StateRepository;
use App\Repository\AdressRepository;
use App\Repository\CartRepository;
use App\Repository\PhotosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;

class CartService
{
    public function __construct(
        private EntityManagerInterface    $em,
        private readonly StateRepository  $stateRepo,
        private readonly Security         $security,
        private readonly PhotosRepository $photoRepo,
        private AdressRepository          $adressRepo,
        private FormFactoryInterface      $formFactory,
        private CartRepository            $cartRepo,
    )
    {
    }

    /**
     * Return array with specific user's id, command object and a form with user's adresses
     */
    public function GetUserData()
    {
        $user = $this->security->getUser();
        $id = $user->getId();
        $orders = new Orders();
        $userAdresses = $this->adressRepo->findBy(['users' => $user]);
        $form = $this->formFactory->create(CommandeFormType::class, $orders, [
            'adressesUtilisateur' => $userAdresses,
        ]);
        $cart = $this->cartRepo->getLastCart($id);
        $result = [
            'form' => $form,
            'Orders' => $orders,
            'id' => $id,
            'cart' => $cart,
        ];
        return $result;
    }

    /**
     * returns an array with cart's price and products datas
     */

    public function CalculCart()
    {
        $carts = $this->GetUserData()['cart'];
        $total = 0;
        $products = [];
        foreach ($carts->getCartProducts() as $cart) {

            $products[] = [
                'id' => $cart->getId(),
                'produit' => $cart->getProduct(),
                'qte' => $cart->getQuantity(),
                'photo' => $this->photoRepo->searchOnePhotoByProduct($cart->getProduct()->getId()),
                'prixTTC' => $cart->getProduct()->getTaxFreePrice() + ($cart->getProduct()->getTaxFreePrice() * $cart->getProduct()->getTaxRate()->getTaxRate() / 100),
            ];
            $total += ($cart->getProduct()->getTaxFreePrice() + ($cart->getProduct()->getTaxFreePrice() * $cart->getProduct()->getTaxRate()->getTaxRate() / 100)) * $cart->getQuantity();
            $total = number_format($total, 2, '.', '');
        }
        return [
            'total' => $total,
            'produits' => $products,
        ];
    }

    public function FormOrdersValidation($request)
    {
        $cart = $this->GetUserData()['cart'];
        $Orders = $this->GetUserData()['Orders'];
        $form = $this->GetUserData()['form'];
        $total = $this->CalculCart()['total'];
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $Orders->setLivraison($data->getLivraison());
            $Orders->setPaiement($data->getPaiement());
            $etatUnique = $this->stateRepo->find(['id' => 1]);
            $Orders->setEstFacture($data->getEstFacture());
            $Orders->setEstLivre($data->getEstLivre());
            $Orders->setEtat($etatUnique);
            $Orders->setUsers($this->security->getUser());
            $Orders->setPanier($cart);
            $Orders->setDateOrders(new \DateTimeImmutable());
            $Orders->setPrixTtcOrders($total);
        
            foreach ($cart->getPanierProduits() as $cart) {
                $ligneOrders = new OrderLine();
                $ligneOrders->setOrders($Orders);
                $ligneOrders->setNomProduit($cart->getProduct()->getlibelle());
                $ligneOrders->setPrixProduit($cart->getProduct()->getPrixHt());
                $ligneOrders->setTauxTva($cart->getProduct()->getTVA()->getTauxTva());
                $ligneOrders->setNombreArticle($cart->getQuantite());
                $ligneOrders->setPrixTotal($total);

                $utilisateur = $Orders->getUsers();
                if ($utilisateur) {
                    $ligneOrders->setNomUtilisateur($utilisateur->getNom());
                    $ligneOrders->setPrenomUtilisateur($utilisateur->getPrenom());
                    $ligneOrders->setEmailUtilisateur($utilisateur->getEmail());
                }
                $this->em->persist($ligneOrders);
            }
            $this->em->persist($Orders);

            $this->em->flush();
            return true;
        }
        return false;
    }


}

