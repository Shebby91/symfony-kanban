<?php

namespace App\Entity;

use App\Repository\BoardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoardRepository::class)]
class Board
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'boards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    /**
     * @var Collection<int, Lane>
     */
    #[ORM\OneToMany(targetEntity: Lane::class, mappedBy: 'board')]
    private Collection $lanes;

    /**
     * @var Collection<int, Label>
     */
    #[ORM\OneToMany(targetEntity: Label::class, mappedBy: 'board')]
    private Collection $labels;

    public function __construct()
    {
        $this->lanes = new ArrayCollection();
        $this->labels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Lane>
     */
    public function getLanes(): Collection
    {
        return $this->lanes;
    }

    public function addLane(Lane $lane): static
    {
        if (!$this->lanes->contains($lane)) {
            $this->lanes->add($lane);
            $lane->setBoard($this);
        }

        return $this;
    }

    public function removeLane(Lane $lane): static
    {
        if ($this->lanes->removeElement($lane)) {
            // set the owning side to null (unless already changed)
            if ($lane->getBoard() === $this) {
                $lane->setBoard(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Label>
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(Label $label): static
    {
        if (!$this->labels->contains($label)) {
            $this->labels->add($label);
            $label->setBoard($this);
        }

        return $this;
    }

    public function removeLabel(Label $label): static
    {
        if ($this->labels->removeElement($label)) {
            // set the owning side to null (unless already changed)
            if ($label->getBoard() === $this) {
                $label->setBoard(null);
            }
        }

        return $this;
    }
}
