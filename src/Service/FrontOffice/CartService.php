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
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;

// Service pour gérer le panier dans le front-office.
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
    // Methode pour obtenir les données de l'utilisateur, de la commande et du formulaire avec les adresses de l'utilisateur.
    public function GetUserData()
    {
        $user = $this->security->getUser();
        if ($user) {
            $id = $user->getId();
            $orders = new Orders();
            $userAdresses = $this->adressRepo->findBy(['users' => $user]);
            $form = $this->formFactory->create(CommandeFormType::class, $orders, [
                'adressesUtilisateur' => $userAdresses,
            ]);
            $cart = $this->cartRepo->getLastCartOrder($id);
            $result = [
                'form' => $form,
                'Orders' => $orders,
                'id' => $id,
                'cart' => $cart,
            ];
            return $result;
        }
        return false;
    }

    /**
     * returns an array with cart's price and products datas
     */

    // Methode pour calculer le panier.
    public function CalculCart()
    {
        $carts = $this->GetUserData()['cart'];
        $total = 0;
        $products = [];

        if ($carts) {
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
        }
        
        return [
            'total' => $total,
            'products' => $products,
        ];
    }

    /**
     * Validate the form and create the order
     */
    // Methode pour valider le formulaire et créer la commande.
    public function FormOrdersValidation($request)
    {
        $cart = $this->GetUserData()['cart'];
        $Orders = $this->GetUserData()['Orders'];
        $form = $this->GetUserData()['form'];
        $total = $this->CalculCart()['total'];
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $Orders->setDelivery($data->getDelivery());
            $Orders->setPayment($data->getPayment());
            $etatUnique = $this->stateRepo->find(['id' => 1]);
            $Orders->setBilled($data->getBilled());
            $Orders->setDelivered($data->getDelivered());
            $Orders->setState($etatUnique);
            $Orders->setUsers($this->security->getUser());
            $Orders->setCart($cart);
            $Orders->setOrderDate(new \DateTimeImmutable());
            $Orders->setTIOrderPrice($total);

            foreach ($cart->getCartProducts() as $cart) {
                $ligneOrders = new OrderLine();
                $ligneOrders->setOrder($Orders);
                $ligneOrders->setProductName($cart->getProduct()->gettitle());
                $ligneOrders->setProductPrice($cart->getProduct()->getTaxFreePrice());
                $ligneOrders->setTaxRate($cart->getProduct()->getTaxRate()->getTaxRate());
                $ligneOrders->setQuantity($cart->getQuantity());
                $ligneOrders->setTotalPrice($total);

                $users = $Orders->getUsers();
                if ($users) {
                    $ligneOrders->setUserLastname($users->getLastname());
                    $ligneOrders->setUserFirstname($users->getFirstname());
                    $ligneOrders->setUserEmail($users->getEmail());
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

