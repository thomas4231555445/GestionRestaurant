<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_users = null;

    #[ORM\Column]
    private ?int $id_basevin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $likeit = null;

    #[ORM\ManyToOne(targetEntity: BaseVins::class, inversedBy: "likes")]
    #[ORM\JoinColumn(nullable: false)]
    private $basevin;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: "boolean")]
    private $liked;

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

    public function getIdBasevin(): ?int
    {
        return $this->id_basevin;
    }

    public function setIdBasevin(int $id_basevin): static
    {
        $this->id_basevin = $id_basevin;

        return $this;
    }

    public function getLikeit(): ?string
    {
        return $this->likeit;
    }

    public function setLikeit(?string $likeit): static
    {
        $this->likeit = $likeit;

        return $this;
    }

    public function getBasevin(): ?BaseVins
    {
        return $this->basevin;
    }

    public function setBasevin(?BaseVins $basevin): static
    {
        $this->basevin = $basevin;

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

    public function isLiked(): ?bool
    {
        return $this->liked;
    }

    public function setLiked(bool $liked): static
    {
        $this->liked = $liked;

        return $this;
    }

    public function setPseudo($getPseudo)
    {
        $this->likeit = $getPseudo;

        return $this;
    }
}

