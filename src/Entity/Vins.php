<?php

namespace App\Entity;

use App\Repository\VinsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VinsRepository::class)]
class Vins
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?int $id_restaurant = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;


    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'appellation ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "L'appellation ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $appellation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du producteur ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le nom du producteur ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $nom_producteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le domaine ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $domaine = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le nom du vin ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $nom_vin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le volume en cl ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le volume en cl ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+(\.[0-9]+)?$/", message: "Le quantité doit être un nombre.")]
    private ?string $cl = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le millésime ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le millésime ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+(\.[0-9]+)?$/", message: "Le millesime doit être un nombre.")]
    private ?string $millesime = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le code vin ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le code vin ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $code_vin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prix d'achat HT ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le prix d'achat HT ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+(\.[0-9]+)?$/", message: "Le prix d'achat HT doit être un nombre.")]
    private ?string $prix_achat_ht = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prix d'achat TTC ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le prix d'achat TTC ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+(\.[0-9]+)?$/", message: "Le prix d'achat TTC doit être un nombre.")]
    private ?string $prix_achat_ttc = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prix de vente HT ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le prix de vente HT ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+(\.[0-9]+)?$/", message: "Le prix de vente HT doit être un nombre.")]
    private ?string $prix_vente_ht = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prix de vente TTC ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le prix de vente TTC ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+(\.[0-9]+)?$/", message: "Le prix de vente TTC doit être un nombre.")]
    private ?string $prix_vente_ttc = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le stock ne peut pas être vide.")]
    #[Assert\Type(type: "integer", message: "Le stock doit être un nombre entier.")]
    #[Assert\Regex(pattern: "/^[0-9]+(\.[0-9]+)?$/", message: "Le stock doit être un nombre.")]
    private ?int $stock = null;

    #[ORM\Column(length: 255)]
    private ?int $id_fournisseur = null;

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: "vins")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    #[ORM\ManyToOne(targetEntity: Notes::class, inversedBy: "vins")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Notes $notes = null;

    #[ORM\ManyToOne(targetEntity: Fournisseurs::class, inversedBy: "vins")]
    #[ORM\JoinColumn(name:"id_fournisseur", referencedColumnName:"id",nullable: false)]
    private $fournisseur = null;



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

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAppellation(): ?string
    {
        return $this->appellation;
    }

    public function setAppellation(string $appellation): static
    {
        $this->appellation = $appellation;

        return $this;
    }

    public function getNomProducteur(): ?string
    {
        return $this->nom_producteur;
    }

    public function setNomProducteur(string $nom_producteur): static
    {
        $this->nom_producteur = $nom_producteur;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(?string $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getNomVin(): ?string
    {
        return $this->nom_vin;
    }

    public function setNomVin(?string $nom_vin): static
    {
        $this->nom_vin = $nom_vin;

        return $this;
    }

    public function getCl(): ?string
    {
        return $this->cl;
    }

    public function setCl(string $cl): static
    {
        $this->cl = $cl;

        return $this;
    }

    public function getMillesime(): ?string
    {
        return $this->millesime;
    }

    public function setMillesime(string $millesime): static
    {
        $this->millesime = $millesime;

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

    public function getPrixAchatHt(): ?string
    {
        return $this->prix_achat_ht;
    }

    public function setPrixAchatHt(string $prix_achat_ht): static
    {
        $this->prix_achat_ht = $prix_achat_ht;

        return $this;
    }

    public function getPrixAchatTtc(): ?string
    {
        return $this->prix_achat_ttc;
    }

    public function setPrixAchatTtc(string $prix_achat_ttc): static
    {
        $this->prix_achat_ttc = $prix_achat_ttc;

        return $this;
    }

    public function getPrixVenteHt(): ?string
    {
        return $this->prix_vente_ht;
    }

    public function setPrixVenteHt(string $prix_vente_ht): static
    {
        $this->prix_vente_ht = $prix_vente_ht;

        return $this;
    }

    public function getPrixVenteTtc(): ?string
    {
        return $this->prix_vente_ttc;
    }

    public function setPrixVenteTtc(string $prix_vente_ttc): static
    {
        $this->prix_vente_ttc = $prix_vente_ttc;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

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



    public function getFournisseur(): ?Fournisseurs
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseurs $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }



}
