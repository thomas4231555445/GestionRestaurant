<?php

namespace App\Entity;

use App\Repository\BaseVinsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BaseVinsRepository::class)]
class BaseVins
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $appellation = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_producteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $domaine = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_vin = null;

    #[ORM\Column(length: 255)]
    private ?string $cl = null;

    #[ORM\Column(length: 255)]
    private ?string $millesime = null;

    #[ORM\Column(length: 900)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $contact = null;

    #[ORM\Column(length: 3)]
    private ?string $actus = null;

    #[ORM\ManyToOne(targetEntity: Notes::class, inversedBy: "basevins")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Notes $notes = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotes(): ?Notes
    {
        return $this->notes;
    }

    public function setNotes(?Notes $notes): static
    {
        $this->notes = $notes;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getActus(): ?string
    {
        return $this->actus;
    }

    public function setActus(string $actus): static
    {
        $this->actus = $actus;
        return $this;

    }

    /**
     * @return Collection<int, Notes>
     */
    public function getNotesCollection(): Collection
    {
        return $this->notesCollection;
    }

    public function setNotesCollection(Collection $notesCollection): static
    {
        $this->notesCollection = $notesCollection;

        return $this;
    }




}
