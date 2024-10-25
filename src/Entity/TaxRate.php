<?php

namespace App\Entity;

use App\Repository\TaxRateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxRateRepository::class)]
class TaxRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $tax_rate = null;

    #[ORM\OneToMany(mappedBy: 'TaxRate', targetEntity: Product::class)]
    private Collection $Product;

    public function __construct()
    {
        $this->Product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxRate(): ?float
    {
        return $this->tax_rate;
    }

    public function setTaxRate(float $tax_rate): static
    {
        $this->tax_rate = $tax_rate;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduct(): Collection
    {
        return $this->Product;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->Product->contains($product)) {
            $this->Product->add($product);
            $product->setTaxRate($this);
        }

        return $this;
    }

    public function removeProduit(Product $product): static
    {
        if ($this->Product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getTaxRate() === $this) {
                $product->setTaxRate(null);
            }
        }

        return $this;
    }
}
