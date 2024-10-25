<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\OneToOne(mappedBy: 'Cart', cascade: ['persist', 'remove'])]
    private ?Orders $order = null;

    #[ORM\ManyToOne(inversedBy: 'Carts')]
    private ?Users $Users = null;

    #[ORM\OneToMany(mappedBy: 'Cart', targetEntity: CartProduct::class)]
    private Collection $cartProducts;



    public function __construct()
    {
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection<int, Produit>
     */





    public function getOrder(): ?Orders
    {
        return $this->order;
    }

    public function setOrder(?Orders $order): static
    {
        // unset the owning side of the relation if necessary
        if ($order === null && $this->order !== null) {
            $this->order->setCart(null);
        }

        // set the owning side of the relation if necessary
        if ($order !== null && $order->getCart() !== $this) {
            $order->setCart($this);
        }

        $this->order = $order;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->Users;
    }

    public function setUsers(?Users $Users): static
    {
        $this->Users = $Users;

        return $this;
    }

    /**
     * @return Collection<int, PanierProduit>
     */
    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function addCartProduct(CartProduct $cartProduct): static
    {
        if (!$this->cartProducts->contains($cartProduct)) {
            $this->cartProducts->add($cartProduct);
            $cartProduct->setCart($this);
        }

        return $this;
    }

    public function removeCartProduct(CartProduct $cartProduct): static
    {
        if ($this->cartProducts->removeElement($cartProduct)) {
            // set the owning side to null (unless already changed)
            if ($cartProduct->getCart() === $this) {
                $cartProduct->setCart(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
    }
}
