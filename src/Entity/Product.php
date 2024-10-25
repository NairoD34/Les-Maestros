<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $tax_free_price = null;



    #[ORM\ManyToOne(inversedBy: 'Product')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TaxRate $TaxRate = null;


    #[ORM\ManyToOne(inversedBy: 'Product')]
    private ?Sales $sales = null;


    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Photos::class, orphanRemoval:true, cascade:["persist"])]
    private Collection $Photos;

    #[ORM\OneToMany(mappedBy: 'Produit', targetEntity: CartProduct::class)]
    private Collection $cartProducts;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    public function __construct()
    {

        $this->Photos = new ArrayCollection();
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /*public function __toString()
    {
        return $this->nom . ' ' . $this->prenom;
    }
    */

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTaxFreePrice(): ?float
    {
        return $this->tax_free_price;
    }

    public function setTaxFreePrice(float $tax_free_price): static
    {
        $this->tax_free_price = $tax_free_price;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */


    public function getTaxRate(): ?TaxRate
    {
        return $this->TaxRate;
    }

    public function setTaxRate(?TaxRate $TaxRate): static
    {
        $this->TaxRate = $TaxRate;

        return $this;
    }

    /**
     * @return Collection<int, Photos>
     */


    public function getSales(): ?Sales
    {
        return $this->sales;
    }

    public function setSales(?Sales $sales): static
    {
        $this->sales = $sales;

        return $this;
    }



    /**
     * @return Collection<int, Photos>
     */
    public function getPhotos(): Collection
    {
        return $this->Photos;
    }

    public function addPhoto(Photos $photo): static
    {
        if (!$this->Photos->contains($photo)) {
            $this->Photos->add($photo);
            $photo->setProduct($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): static
    {
        if ($this->Photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getProduct() === $this) {
                $photo->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PanierProduit>
     */
    public function getCartProduct(): Collection
    {
        return $this->cartProducts;
    }

    public function addCartProduct(CartProduct $cartProduct): static
    {
        if (!$this->cartProducts->contains($cartProduct)) {
            $this->cartProducts->add($cartProduct);
            $cartProduct->setProduct($this);
        }

        return $this;
    }

    public function removeCartProduct(CartProduct $cartProduct): static
    {
        if ($this->cartProducts->removeElement($cartProduct)) {
            // set the owning side to null (unless already changed)
            if ($cartProduct->getProduct() === $this) {
                $cartProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
