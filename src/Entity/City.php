<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 255)]
    private ?string $name = null;



    #[ORM\ManyToOne(inversedBy: 'City')]
    #[ORM\JoinColumn(nullable: false)]
    private ?County $County = null;

    #[ORM\OneToMany(mappedBy: 'City', targetEntity: Adress::class)]
    private Collection $adress;

    #[ORM\ManyToMany(targetEntity: ZIPcode::class, inversedBy: 'cities')]
    private Collection $ZIPcode;

    public function __construct()
    {
        $this->adress = new ArrayCollection();
        $this->ZIPcode = new ArrayCollection();
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
     * @return Collection<int, Adresse>
     */


    public function getCounty(): ?County
    {
        return $this->County;
    }

    public function setCounty(?County $County): static
    {
        $this->County = $County;

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdress(): Collection
    {
        return $this->adress;
    }

    public function addAdress(Adress $adress): static
    {
        if (!$this->adress->contains($adress)) {
            $this->adress->add($adress);
            $adress->setCity($this);
        }

        return $this;
    }

    public function removeAdress(Adress $adress): static
    {
        if ($this->adress->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getCity() === $this) {
                $adress->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CodePostal>
     */
    public function getZIPcode(): Collection
    {
        return $this->ZIPcode;
    }

    public function addZIPcode(ZIPcode $ZIPcode): static
    {
        if (!$this->ZIPcode->contains($ZIPcode)) {
            $this->ZIPcode->add($ZIPcode);
        }

        return $this;
    }

    public function removeZIPcode(ZIPcode $ZIPcode): static
    {
        $this->ZIPcode->removeElement($ZIPcode);

        return $this;
    }
}
