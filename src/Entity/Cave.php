<?php

namespace App\Entity;

use App\Repository\CaveRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaveRepository::class)]
class Cave
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $id_restaurant = null;

    #[ORM\Column(length: 255)]
    private ?string $num_cave = null;


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


    public function getNumCave(): ?string
    {
        return $this->num_cave;
    }

    public function setNumCave(string $num_cave): static
    {
        $this->num_cave = $num_cave;

        return $this;
    }


}