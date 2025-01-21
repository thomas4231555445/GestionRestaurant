<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_restaurant = null;

    #[ORM\Column]
    private ?int $id_fournisseur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_fournisseur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_commande = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdFournisseur(): ?int
    {
        return $this->id_fournisseur;
    }

    public function setIdFournisseur(int $id_fournisseur): static
    {
        $this->id_fournisseur = $id_fournisseur;

        return $this;
    }

    public function getNomFournisseur(): ?string
    {
        return $this->nom_fournisseur;
    }

    public function setNomFournisseur(?string $nom_fournisseur): static
    {
        $this->nom_fournisseur = $nom_fournisseur;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeInterface $date_commande): static
    {
        $this->date_commande = $date_commande;

        return $this;
    }
}
