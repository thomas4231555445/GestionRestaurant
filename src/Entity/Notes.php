<?php

namespace App\Entity;

use App\Repository\NotesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
class Notes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_vin = null;

    #[ORM\Column(nullable: true)]
    private ?int $fruit = null;

    #[ORM\Column(nullable: true)]
    private ?int $leger = null;

    #[ORM\Column(nullable: true)]
    private ?int $fraicheur = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_users = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable:false)]
    private $user;

    private $pseudo;

    #[ORM\OneToMany(targetEntity: BaseVins::class, mappedBy: "notes")]
    private Collection $basevins;

    public function __construct()
    {
        $this->basevins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getIdVin(): ?int
    {
        return $this->id_vin;
    }

    public function setIdVin(int $id_vin): static
    {
        $this->id_vin = $id_vin;

        return $this;
    }

    public function getFruit(): ?int
    {
        return $this->fruit;
    }

    public function setFruit(?int $fruit): static
    {
        $this->fruit = $fruit;

        return $this;
    }

    public function getLeger(): ?int
    {
        return $this->leger;
    }

    public function setLeger(?int $leger): static
    {
        $this->leger = $leger;

        return $this;
    }

    public function getFraicheur(): ?int
    {
        return $this->fraicheur;
    }

    public function setFraicheur(?int $fraicheur): static
    {
        $this->fraicheur = $fraicheur;

        return $this;
    }

    public function getIdUsers(): ?int
    {
        return $this->id_users;
    }

    public function setIdUsers(int $id_users): static
    {
        $this->id_users = $id_users;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }



    /**
     * @return Collection<int, BaseVins>
     */
    public function getBaseVins(): Collection
    {
        return $this->basevins;
    }

    public function addBaseVins(BaseVins $basevins): static
    {
        if (!$this->basevins->contains($basevins)) {
            $this->basevins->add($basevins);
            $basevins->setNotes($this);
        }

        return $this;
    }

    public function removeBaseVins(BaseVins $basevins): static
    {
        if ($this->basevins->removeElement($basevins)) {
            // set the owning side to null (unless already changed)
            if ($basevins->getNotes() === $this) {
                $basevins->setNotes(null);
            }
        }

        return $this;
    }
}
