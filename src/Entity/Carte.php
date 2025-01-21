<?php

namespace App\Entity;

use App\Repository\CarteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarteRepository::class)]
class Carte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $id_restaurant = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $background = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_etablissement = null;

    #[ORM\Column(type:"text", nullable: true)]
    private ?string $selection = null;

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: "carte")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    #[ORM\ManyToMany(targetEntity: Vins::class, inversedBy:"cartes")]
    private Collection $vins;

    public function __construct()
    {
        $this->vins = new ArrayCollection();
    }

    public function getVins(): Collection
    {
        return $this->vins;
    }

    public function addVin(Vins $vin): self
    {
        if (!$this->vins->contains($vin)) {
            $this->vins[] = $vin;
        }

        return $this;
    }

    public function removeVin(Vins $vin): self
    {
        $this->vins->removeElement($vin);

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRestaurant(): ?string
    {
        return $this->id_restaurant;
    }

    public function setIdRestaurant(string $id_restaurant): static
    {
        $this->id_restaurant = $id_restaurant;

        return $this;
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

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(?string $background): static
    {
        $this->background = $background;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getNomEtablissement(): ?string
    {
        return $this->nom_etablissement;
    }

    public function setNomEtablissement(string $nom_etablissement): static
    {
        $this->nom_etablissement = $nom_etablissement;

        return $this;
    }


    public function getSelection(): ?string
    {
        return $this->selection;
    }

    public function setSelection(string $selection): static
    {
        $this->selection = $selection;

        return $this;
    }

    public function setSelectionFromArray(array $selectionArray): self
    {
        $this->selection = implode(', ', $selectionArray);

        return $this;
    }


    public function getSelectionAsArray(): array
    {
        return $this->selection ? explode(', ', $this->selection) : [];
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }


}
