<?php

namespace App\Entity;

use App\Repository\FournisseursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FournisseursRepository::class)]
class Fournisseurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le SIREN ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $siren = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le téléphone ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+$/", message: "Le téléphone doit contenir uniquement des chiffres.")]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email(message: "L'adresse e-mail '{{ value }}' n'est pas valide.")]
    #[Assert\Length(max: 255, maxMessage: "L'adresse e-mail ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $mail = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le pays ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $pays = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le code postal ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+$/", message: "Le code postal doit contenir uniquement des chiffres.")]
    private ?string $code_postale = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La ville ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "La ville ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le nom de famille ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $nom_famille = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le téléphone personnel ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+$/", message: "Le téléphone personnel doit contenir uniquement des chiffres.")]
    private ?string $telephoneperso = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email(message: "L'adresse e-mail personnelle '{{ value }}' n'est pas valide.")]
    #[Assert\Length(max: 255, maxMessage: "L'adresse e-mail personnelle ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $mailperso = null;

    #[ORM\Column(length: 255)]
    private ?int $id_restaurant = null;

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


    public function getNom(): ?string
    {
        return $this->nom;
    }


    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): static
    {
        $this->siren = $siren;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostale(): ?string
    {
        return $this->code_postale;
    }

    public function setCodePostale(?string $code_postale): static
    {
        $this->code_postale = $code_postale;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNomFamille(): ?string
    {
        return $this->nom_famille;
    }

    public function setNomFamille(?string $nom_famille): static
    {
        $this->nom_famille = $nom_famille;

        return $this;
    }

    public function getTelephoneperso(): ?string
    {
        return $this->telephoneperso;
    }

    public function setTelephoneperso(?string $telephoneperso): static
    {
        $this->telephoneperso = $telephoneperso;

        return $this;
    }

    public function getMailperso(): ?string
    {
        return $this->mailperso;
    }

    public function setMailperso(?string $mailperso): static
    {
        $this->mailperso = $mailperso;

        return $this;
    }

    public function getNomFournisseur()
    {
    }
}
