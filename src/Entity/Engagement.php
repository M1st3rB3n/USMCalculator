<?php

namespace App\Entity;

use App\Repository\EngagementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EngagementRepository::class)]
#[UniqueEntity(
    fields: ['patineuse', 'epreuve'],
    message: 'Cette patineuse est déjà engagée dans cette épreuve.'
)]
class Engagement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'engagements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Veuillez sélectionner une patineuse.')]
    private ?Patineuse $patineuse = null;

    #[ORM\ManyToOne(inversedBy: 'engagements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Veuillez sélectionner une épreuve.')]
    private ?Epreuve $epreuve = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatineuse(): ?Patineuse
    {
        return $this->patineuse;
    }

    public function setPatineuse(?Patineuse $patineuse): static
    {
        $this->patineuse = $patineuse;

        return $this;
    }

    public function getEpreuve(): ?Epreuve
    {
        return $this->epreuve;
    }

    public function setEpreuve(?Epreuve $epreuve): static
    {
        $this->epreuve = $epreuve;

        return $this;
    }

    public function getTotalNoteTechnique(): float
    {
        $total = 0;
        foreach ($this->patineuse->getNoteTechniques() as $note) {
            if ($note->getEpreuve() === $this->epreuve) {
                $total += $note->getNote() ?? 0;
            }
        }
        return $total;
    }

    public function getTotalNoteArtistique(): float
    {
        $total = 0;
        foreach ($this->patineuse->getNoteArtistiques() as $note) {
            if ($note->getEpreuve() === $this->epreuve) {
                $total += $note->getNote() ?? 0;
            }
        }
        return $total;
    }

    public function getTotalNotes(): float
    {
        return $this->getTotalNoteTechnique() + $this->getTotalNoteArtistique();
    }
}
