<?php

namespace App\Entity;

use App\Repository\NoteArtistiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteArtistiqueRepository::class)]
class NoteArtistique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    #[ORM\ManyToOne(inversedBy: 'noteArtistiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patineuse $patineuse = null;

    #[ORM\ManyToOne(inversedBy: 'noteArtistiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ElementArtistique $elementArtistique = null;

    #[ORM\ManyToOne(inversedBy: 'noteArtistiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Epreuve $epreuve = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): static
    {
        $this->note = $note;

        return $this;
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

    public function getElementArtistique(): ?ElementArtistique
    {
        return $this->elementArtistique;
    }

    public function setElementArtistique(?ElementArtistique $elementArtistique): static
    {
        $this->elementArtistique = $elementArtistique;

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
}
