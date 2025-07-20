<?php

namespace App\Entity;

use App\Repository\CardAssignmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardAssignmentRepository::class)]
class CardAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cardAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Card $card = null;

    #[ORM\ManyToOne(inversedBy: 'cardAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $assignedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): static
    {
        $this->card = $card;

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

    public function getAssignedAt(): ?\DateTimeImmutable
    {
        return $this->assignedAt;
    }

    public function setAssignedAt(\DateTimeImmutable $assignedAt): static
    {
        $this->assignedAt = $assignedAt;

        return $this;
    }
}
