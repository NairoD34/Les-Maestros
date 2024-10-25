<?php

namespace App\Entity;

use App\Repository\PhotosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotosRepository::class)]
class Photos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 255)]
    private ?string $URL_photo = null;

    #[ORM\ManyToOne(inversedBy: 'Photos')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'Photos')]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getURLPhoto(): ?string
    {
        return $this->URL_photo;
    }

    public function setURLPhoto(string $URL_photo): static
    {
        $this->URL_photo = $URL_photo;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
