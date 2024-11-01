<?php

namespace App\Entity;

use App\Repository\SalesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalesRepository::class)]
class Sales
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?float $sales_rate = null;

    #[ORM\Column(length: 255)]
    private ?string $sales_code = null;

    #[ORM\OneToMany(mappedBy: 'sales', targetEntity: Product::class)]
    private Collection $Product;

    public function __construct()
    {
        $this->Product = new ArrayCollection();
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

    public function getSalesRate(): ?float
    {
        return $this->sales_rate;
    }

    public function setSalesRate(float $sales_rate): static
    {
        $this->sales_rate = $sales_rate;

        return $this;
    }

    public function getSalesCode(): ?string
    {
        return $this->sales_code;
    }

    public function setSalesCode(string $sales_code): static
    {
        $this->sales_code = $sales_code;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProduct(): Collection
    {
        return $this->Product;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->Product->contains($product)) {
            $this->Product->add($product);
            $product->setSales($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->Product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getSales() === $this) {
                $product->setSales(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title ?? 'Sales';
    }
}
