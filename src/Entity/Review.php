<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $moderated = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?User $userReview = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Product $priductReview = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

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

    public function getModerated(): ?string
    {
        return $this->moderated;
    }

    public function setModerated(string $moderated): static
    {
        $this->moderated = $moderated;

        return $this;
    }

    public function getUserReview(): ?User
    {
        return $this->userReview;
    }

    public function setUserReview(?User $userReview): static
    {
        $this->userReview = $userReview;

        return $this;
    }

    public function getPriductReview(): ?Product
    {
        return $this->priductReview;
    }

    public function setPriductReview(?Product $priductReview): static
    {
        $this->priductReview = $priductReview;

        return $this;
    }
}
