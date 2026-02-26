<?php

namespace App\Entity;

use App\Repository\ElementArtistiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElementArtistiqueRepository::class)]
class ElementArtistique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?float $score = null;

    #[ORM\Column(nullable: true)]
    private ?float $QoE = null;

    #[ORM\OneToMany(targetEntity: NoteArtistique::class, mappedBy: 'elementArtistique', orphanRemoval: true)]
    private Collection $noteArtistiques;

    public function __construct()
    {
        $this->noteArtistiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getQoE(): ?float
    {
        return $this->QoE;
    }

    public function setQoE(?float $QoE): static
    {
        $this->QoE = $QoE;

        return $this;
    }

    /**
     * @return Collection<int, NoteArtistique>
     */
    public function getNoteArtistiques(): Collection
    {
        return $this->noteArtistiques;
    }

    public function addNoteArtistique(NoteArtistique $noteArtistique): static
    {
        if (!$this->noteArtistiques->contains($noteArtistique)) {
            $this->noteArtistiques->add($noteArtistique);
            $noteArtistique->setElementArtistique($this);
        }

        return $this;
    }

    public function removeNoteArtistique(NoteArtistique $noteArtistique): static
    {
        if ($this->noteArtistiques->removeElement($noteArtistique)) {
            // set the owning side to null (unless already changed)
            if ($noteArtistique->getElementArtistique() === $this) {
                $noteArtistique->setElementArtistique(null);
            }
        }

        return $this;
    }
}
