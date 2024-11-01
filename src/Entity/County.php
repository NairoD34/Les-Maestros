<?php

namespace App\Entity;

use App\Repository\CountyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountyRepository::class)]
class County
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $ZIP = null;

    #[ORM\OneToMany(mappedBy: 'County', targetEntity: City::class)]
    private Collection $City;

    #[ORM\ManyToOne(inversedBy: 'County')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

    public function __construct()
    {
        $this->City = new ArrayCollection();
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

    public function getZIP(): ?string
    {
        return $this->ZIP;
    }

    public function setZIP(int $ZIP): static
    {
        $this->ZIP = $ZIP;

        return $this;
    }

    /**
     * @return Collection<int, Ville>
     */
    public function getCity(): Collection
    {
        return $this->City;
    }

    public function addCity(City $city): static
    {
        if (!$this->City->contains($city)) {
            $this->City->add($city);
            $city->setCounty($this);
        }

        return $this;
    }

    public function removeCity(City $city): static
    {
        if ($this->City->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getCounty() === $this) {
                $city->setCounty(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }
}
