<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'child_category')]
    private ?self $parent_category = null;

    #[ORM\OneToMany(mappedBy: 'parent_category', targetEntity: self::class)]
    private Collection $child_category;

    

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Photos::class, orphanRemoval:true, cascade:["persist"])]
    private Collection $Photos;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class)]
    private Collection $products;

    public function __construct()
    {
        $this->child_category = new ArrayCollection();
        
        $this->Photos = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getParentCategory(): ?self
    {
        return $this->parent_category;
    }

    public function setParentCategory(?self $parent_category): static
    {
        $this->parent_category = $parent_category;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildCategory(): Collection
    {
        return $this->child_category;
    }

    public function addChildCategory(self $child_category): static
    {
        if (!$this->child_category->contains($child_category)) {
            $this->child_category->add($child_category);
            $child_category->setParentCategory($this);
        }

        return $this;
    }

    public function removeChildCategory(self $child_category): static
    {
        if ($this->child_category->removeElement($child_category)) {
            // set the owning side to null (unless already changed)
            if ($child_category->getParentCategory() === $this) {
                $child_category->setParentCategory(null);
            }
        }

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
            $photo->setCategory($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): static
    {
        if ($this->Photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getCategory() === $this) {
                $photo->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}
