<?php

// src/Entity/Post.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:"App\Repository\PostRepository")]

class Post
{
     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column(type:"integer")]

    private $id;

    #[ORM\Column(type:"text")]
    private $comments;

    #[ORM\Column(type:"integer")]
    private $id_users;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $avatar = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_users", referencedColumnName: "id", nullable: false)]
    private $user;


    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getIdUsers(): ?int
    {
        return $this->id_users;
    }

    public function setIdUsers(int $id_users): self
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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getUserRoles(): array
    {
        return $this->getUser()->getRoles();
    }

    public function setUser(?User $user)
    {
        $this->user = $user;
        return $this;
    }
}
