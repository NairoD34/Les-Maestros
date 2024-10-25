<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: County::class)]
    private Collection $County;

    public function __construct()
    {
        $this->County = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Departement>
     */
    public function getCounty(): Collection
    {
        return $this->County;
    }

    public function addCounty(County $county): static
    {
        if (!$this->County->contains($county)) {
            $this->County->add($county);
            $county->setRegion($this);
        }

        return $this;
    }

    public function removeCounty(County $county): static
    {
        if ($this->County->removeElement($county)) {
            // set the owning side to null (unless already changed)
            if ($county->getRegion() === $this) {
                $county->setRegion(null);
            }
        }

        return $this;
    }
}
