<?php

namespace App\Entity;

use App\Repository\StarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StarRepository::class)]
class Star
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_users = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le champ ne peut etre vide')]
    #[Assert\Range(notInRangeMessage : "La note doit être comprise entre {{ min }} et {{ max }}.", min: 1, max: 5)]
    private ?string $star = null;

    #[ORM\Column]
    private ?int $base_vins_id = null;

    #[ORM\ManyToOne(targetEntity: BaseVins::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?BaseVins $baseVins = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getStar(): ?string
    {
        return $this->star;
    }

    public function setStar(?string $star): static
    {
        $this->star = $star;

        return $this;
    }

    public function getBaseVins(): ?BaseVins
    {
        return $this->baseVins;
    }

    public function setBaseVins(?BaseVins $baseVins): static
    {
        $this->baseVins = $baseVins;

        return $this;
    }

    public function getBaseVinsId(): ?int
    {
        return $this->base_vins_id;
    }

    public function setBaseVinsId(int $base_vins_id): static
    {
        $this->base_vins_id = $base_vins_id;

        return $this;
    }
}
