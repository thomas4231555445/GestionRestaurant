<?php

namespace App\Entity;

use App\Repository\InventaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: InventaireRepository::class)]
class Inventaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $id_restaurant = null;

    #[ORM\Column(length: 255)]
    private ?string $code_vin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La quantité ne peut pas être vide.")]
    #[Assert\Regex(pattern: "/^[0-9]+$/", message: "Le téléphone doit contenir uniquement des chiffres.")]
    private ?string $qts = null;

    #[ORM\Column(length: 255)]
    private ?string $date_enregistrement = null;

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: "inventaires")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;


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

    public function getCodeVin(): ?string
    {
        return $this->code_vin;
    }

    public function setCodeVin(string $code_vin): static
    {
        $this->code_vin = $code_vin;

        return $this;
    }

    public function getQts(): ?string
    {
        return $this->qts;
    }

    public function setQts(string $qts): static
    {
        $this->qts = $qts;

        return $this;
    }

    public function getDateEnregistrement(): ?string
    {
        return $this->date_enregistrement;
    }

    public function setDateEnregistrement(string $date_enregistrement): static
    {
        $this->date_enregistrement = $date_enregistrement;

        return $this;
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


