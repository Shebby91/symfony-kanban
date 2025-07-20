<?php

namespace App\Entity;

use App\Repository\LabelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LabelRepository::class)]
class Label
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 7)]
    private ?string $color = null;

    #[ORM\ManyToOne(inversedBy: 'labels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Board $board = null;

    /**
     * @var Collection<int, CardLabel>
     */
    #[ORM\OneToMany(targetEntity: CardLabel::class, mappedBy: 'label')]
    private Collection $cardLabels;

    public function __construct()
    {
        $this->cardLabels = new ArrayCollection();
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getBoard(): ?Board
    {
        return $this->board;
    }

    public function setBoard(?Board $board): static
    {
        $this->board = $board;

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
            $cardLabel->setLabel($this);
        }

        return $this;
    }

    public function removeCardLabel(CardLabel $cardLabel): static
    {
        if ($this->cardLabels->removeElement($cardLabel)) {
            // set the owning side to null (unless already changed)
            if ($cardLabel->getLabel() === $this) {
                $cardLabel->setLabel(null);
            }
        }

        return $this;
    }
}
