<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lane $lane = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'card')]
    private Collection $comments;

    /**
     * @var Collection<int, CardLabel>
     */
    #[ORM\OneToMany(targetEntity: CardLabel::class, mappedBy: 'card')]
    private Collection $cardLabels;

    /**
     * @var Collection<int, CardAssignment>
     */
    #[ORM\OneToMany(targetEntity: CardAssignment::class, mappedBy: 'card')]
    private Collection $cardAssignments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->cardLabels = new ArrayCollection();
        $this->cardAssignments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getLane(): ?Lane
    {
        return $this->lane;
    }

    public function setLane(?Lane $lane): static
    {
        $this->lane = $lane;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setCard($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getCard() === $this) {
                $comment->setCard(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CardLabel>
     */
    public function getCardLabels(): Collection
    {
        return $this->cardLabels;
    }

    public function addCardLabel(CardLabel $cardLabel): static
    {
        if (!$this->cardLabels->contains($cardLabel)) {
            $this->cardLabels->add($cardLabel);
            $cardLabel->setCard($this);
        }

        return $this;
    }

    public function removeCardLabel(CardLabel $cardLabel): static
    {
        if ($this->cardLabels->removeElement($cardLabel)) {
            // set the owning side to null (unless already changed)
            if ($cardLabel->getCard() === $this) {
                $cardLabel->setCard(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CardAssignment>
     */
    public function getCardAssignments(): Collection
    {
        return $this->cardAssignments;
    }

    public function addCardAssignment(CardAssignment $cardAssignment): static
    {
        if (!$this->cardAssignments->contains($cardAssignment)) {
            $this->cardAssignments->add($cardAssignment);
            $cardAssignment->setCard($this);
        }

        return $this;
    }

    public function removeCardAssignment(CardAssignment $cardAssignment): static
    {
        if ($this->cardAssignments->removeElement($cardAssignment)) {
            // set the owning side to null (unless already changed)
            if ($cardAssignment->getCard() === $this) {
                $cardAssignment->setCard(null);
            }
        }

        return $this;
    }
}
