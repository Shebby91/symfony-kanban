<?php

namespace App\Entity;

use App\Repository\CardLabelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardLabelRepository::class)]
class CardLabel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cardLabels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Card $card = null;

    #[ORM\ManyToOne(inversedBy: 'cardLabels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Label $label = null;

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

    public function getLabel(): ?Label
    {
        return $this->label;
    }

    public function setLabel(?Label $label): static
    {
        $this->label = $label;

        return $this;
    }
}
