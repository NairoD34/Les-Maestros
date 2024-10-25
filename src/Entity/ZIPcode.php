<?php

namespace App\Entity;

use App\Repository\ZIPcodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZIPcodeRepository::class)]
class ZIPcode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[ORM\ManyToMany(targetEntity: City::class, mappedBy: 'ZIPcode')]
    private Collection $cities;

    #[ORM\Column(length: 10)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'ZIPcode', targetEntity: Adress::class)]
    private Collection $adresses;



    public function __construct()
    {
        $this->cities = new ArrayCollection();
        $this->adresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Ville>
     */

    /**
     * @return Collection<int, Ville>
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): static
    {
        if (!$this->cities->contains($city)) {
            $this->cities->add($city);
            $city->addZIPcode($this);
        }

        return $this;
    }

    public function removeCity(City $city): static
    {
        if ($this->cities->removeElement($city)) {
            $city->removeZIPcode($this);
        }

        return $this;
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

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adress $adress): static
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setZIPcode($this);
        }

        return $this;
    }

    public function removeAdress(Adress $adress): static
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getZIPcode() === $this) {
                $adress->setZIPcode(null);
            }
        }

        return $this;
    }
}
