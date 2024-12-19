<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
class Adress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complement = null;

    #[ORM\OneToMany(mappedBy: 'delivered', targetEntity: Orders::class)]
    private Collection $delivered;

    #[ORM\OneToMany(mappedBy: 'billed', targetEntity: Orders::class)]
    private Collection $billed;

    #[ORM\ManyToOne(inversedBy: 'adress')]
    private ?City $City = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    private ?ZIPcode $ZIPcode = null;

    #[ORM\Column]
    private ?bool $isActive = null;


    public function __construct()
    {
        $this->delivered = new ArrayCollection();
        $this->billed = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): static
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getDelivered(): Collection
    {
        return $this->delivered;
    }

    public function addDelivered(Orders $delivered): static
    {
        if (!$this->delivered->contains($delivered)) {
            $this->delivered->add($delivered);
            $delivered->setDelivered($this);
        }

        return $this;
    }

    public function removeDelivered(Orders $delivered): static
    {
        if ($this->est_livre->removeElement($delivered)) {
            // set the owning side to null (unless already changed)
            if ($delivered->getDelivered() === $this) {
                $delivered->setDelivered(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getBilled(): Collection
    {
        return $this->billed;
    }

    public function addBilled(Orders $billed): static
    {
        if (!$this->billed->contains($billed)) {
            $this->billed->add($billed);
            $billed->setBilled($this);
        }

        return $this;
    }

    public function removeBilled(Orders $billed): static
    {
        if ($this->billed->removeElement($billed)) {
            // set the owning side to null (unless already changed)
            if ($billed->getBilled() === $this) {
                $billed->setBilled(null);
            }
        }

        return $this;
    }


    public function getCity(): ?City
    {
        return $this->City;
    }

    public function setCity(?City $City): static
    {
        $this->City = $City;

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

    public function getZIPcode(): ?ZIPcode
    {
        return $this->ZIPcode;
    }

    public function setZIPcode(?ZIPcode $ZIPcode): static
    {
        $this->ZIPcode = $ZIPcode;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }


}
