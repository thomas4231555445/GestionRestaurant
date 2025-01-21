<?php

namespace App\Entity;

use App\Repository\VinsCaveRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VinsCaveRepository::class)]
class VinsCave
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_cave = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code_vin = null;

    #[ORM\Column]
    private ?int $ligne = null;

    #[ORM\Column]
    private ?int $colonne = null;

    #[ORM\Column]
    private ?int $id_restaurant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCave(): ?int
    {
        return $this->id_cave;
    }

    public function setIdCave(int $id_cave): static
    {
        $this->id_cave = $id_cave;

        return $this;
    }

    public function getCodeVin(): ?string
    {
        return $this->code_vin;
    }

    public function setCodeVin(?string $code_vin): static
    {
        $this->code_vin = $code_vin;

        return $this;
    }

    public function getLigne(): ?int
    {
        return $this->ligne;
    }

    public function setLigne(int $ligne): static
    {
        $this->ligne = $ligne;

        return $this;
    }

    public function getColonne(): ?int
    {
        return $this->colonne;
    }

    public function setColonne(int $colonne): static
    {
        $this->colonne = $colonne;

        return $this;
    }

    public function getIdRestaurant(): ?int
    {
        return $this->id_restaurant;
    }

    public function setIdRestaurant(int $id_restaurant): static
    {
        $this->id_restaurant = $id_restaurant;

        return $this;
    }
}
