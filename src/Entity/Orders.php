<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $order_date = null;

    #[ORM\Column]
    private ?float $ti_order_price = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderLine::class)]
    private Collection $orderLine;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Delivery $Delivery = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Payment $Payment = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?State $State = null;

    #[ORM\ManyToOne(inversedBy: 'delivered')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adress $delivered = null;

    #[ORM\ManyToOne(inversedBy: 'billed')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adress $billed = null;

    #[ORM\ManyToOne(inversedBy: 'Order')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\OneToOne(inversedBy: 'order', cascade: ['persist', 'remove'])]
    private ?Cart $Cart = null;

    



    public function __construct()
    {
        $this->orderLine = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeImmutable
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeImmutable $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getTIOrderPrice(): ?float
    {
        return $this->ti_order_price;
    }

    public function setTIOrderPrice(float $ti_order_price): static
    {
        $this->ti_order_price = $ti_order_price;

        return $this;
    }

    /**
     * @return Collection<int, LigneDeCommande>
     */
    public function getOrderLine(): Collection
    {
        return $this->orderLine;
    }

    public function addOrderLine(OrderLine $orderLine): static
    {
        if (!$this->orderLine->contains($orderLine)) {
            $this->orderLine->add($orderLine);
            $orderLine->setOrder($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): static
    {
        if ($this->orderLine->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getOrder() === $this) {
                $orderLine->setOrder(null);
            }
        }

        return $this;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->Delivery;
    }

    public function setDelivery(?Delivery $Delivery): static
    {
        $this->Delivery = $Delivery;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->Payment;
    }

    public function setPayment(?Payment $Payment): static
    {
        $this->Payment = $Payment;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->State;
    }

    public function setState(?State $State): static
    {
        $this->State = $State;

        return $this;
    }

    public function getDelivered(): ?Adress
    {
        return $this->delivered;
    }

    public function setDelivered(?Adress $delivered): static
    {
        $this->delivered = $delivered;

        return $this;
    }

    public function getBilled(): ?Adress
    {
        return $this->billed;
    }

    public function setBilled(?Adress $billed): static
    {
        $this->billed = $billed;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->Cart;
    }

    public function setCart(?Cart $Cart): static
    {
        $this->Cart = $Cart;

        return $this;
    }

    

}
