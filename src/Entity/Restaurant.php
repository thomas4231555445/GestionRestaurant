<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_users = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du restaurant ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le nom du restaurant ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $nom_restaurant = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le code postal ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le code postal ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+$/", message: "Le code postal doit contenir uniquement des chiffres.")]
    private ?string $code_postale = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La ville ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "La ville ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le téléphone ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le téléphone ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^[0-9]+$/", message: "Le téléphone doit contenir uniquement des chiffres.")]
    private ?string $telephone = null;


    #[ORM\OneToMany(targetEntity: Vins::class, mappedBy: "restaurant")]
    private Collection $vins;

    #[ORM\ManyToOne(targetEntity:"App\Entity\User", inversedBy:"restaurant")]
    #[ORM\JoinColumn(nullable:false)]
    private $user;


    #[ORM\OneToMany(targetEntity: Inventaire::class, mappedBy: "restaurant")]
    private Collection $inventaires;

    public function __construct()
    {
        $this->vins = new ArrayCollection();
        $this->inventaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsers(): ?int
    {
        return $this->id_users;
    }

    public function setIdUsers(int $idUsers): static
    {
        $this->id_users = $idUsers;

        return $this;
    }

    public function getNomRestaurant(): ?string
    {
        return $this->nom_restaurant;
    }

    public function setNomRestaurant(string $nomRestaurant): static
    {
        $this->nom_restaurant = $nomRestaurant;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostale(): ?string
    {
        return $this->code_postale;
    }

    public function setCodePostale(string $codePostale): static
    {
        $this->code_postale = $codePostale;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Inventaire>
     */
    public function getInventaires(): Collection
    {
        return $this->inventaires;
    }

    public function addInventaire(Inventaire $inventaire): static
    {
        if (!$this->inventaires->contains($inventaire)) {
            $this->inventaires->add($inventaire);
            $inventaire->setRestaurant($this);
        }

        return $this;
    }

    public function removeInventaire(Inventaire $inventaire): static
    {
        if ($this->inventaires->removeElement($inventaire)) {
            // set the owning side to null (unless already changed)
            if ($inventaire->getRestaurant() === $this) {
                $inventaire->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vins>
     */
    public function getVins(): Collection
    {
        return $this->vins;
    }

    public function addVins(Vins $vins): static
    {
        if (!$this->vins->contains($vins)) {
            $this->vins->add($vins);
            $vins->setRestaurant($this);
        }

        return $this;
    }

    public function removeIVins(Vins $vins): static
    {
        if ($this->vins->removeElement($vins)) {
            // set the owning side to null (unless already changed)
            if ($vins->getRestaurant() === $this) {
                $vins->setRestaurant(null);
            }
        }

        return $this;
    }
}
