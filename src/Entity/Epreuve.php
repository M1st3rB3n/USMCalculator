<?php

namespace App\Entity;

use App\Repository\EpreuveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpreuveRepository::class)]
class Epreuve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'epreuves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'epreuves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Niveau $niveau = null;

    #[ORM\ManyToMany(targetEntity: ElementTechnique::class)]
    private Collection $elementsTechniques;

    #[ORM\ManyToMany(targetEntity: ElementArtistique::class)]
    private Collection $elementsArtistiques;

    #[ORM\OneToMany(targetEntity: Engagement::class, mappedBy: 'epreuve', orphanRemoval: true)]
    private Collection $engagements;

    #[ORM\OneToMany(targetEntity: NoteTechnique::class, mappedBy: 'epreuve', orphanRemoval: true)]
    private Collection $noteTechniques;

    #[ORM\OneToMany(targetEntity: NoteArtistique::class, mappedBy: 'epreuve', orphanRemoval: true)]
    private Collection $noteArtistiques;

    #[ORM\ManyToOne(inversedBy: 'epreuves')]
    private ?Competition $competition = null;

    public function __construct()
    {
        $this->elementsTechniques = new ArrayCollection();
        $this->elementsArtistiques = new ArrayCollection();
        $this->engagements = new ArrayCollection();
        $this->noteTechniques = new ArrayCollection();
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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * @return Collection<int, ElementTechnique>
     */
    public function getElementsTechniques(): Collection
    {
        return $this->elementsTechniques;
    }

    public function addElementsTechnique(ElementTechnique $elementsTechnique): static
    {
        if (!$this->elementsTechniques->contains($elementsTechnique)) {
            $this->elementsTechniques->add($elementsTechnique);
        }

        return $this;
    }

    public function removeElementsTechnique(ElementTechnique $elementsTechnique): static
    {
        $this->elementsTechniques->removeElement($elementsTechnique);

        return $this;
    }

    /**
     * @return Collection<int, ElementArtistique>
     */
    public function getElementsArtistiques(): Collection
    {
        return $this->elementsArtistiques;
    }

    public function addElementsArtistique(ElementArtistique $elementsArtistique): static
    {
        if (!$this->elementsArtistiques->contains($elementsArtistique)) {
            $this->elementsArtistiques->add($elementsArtistique);
        }

        return $this;
    }

    public function removeElementsArtistique(ElementArtistique $elementsArtistique): static
    {
        $this->elementsArtistiques->removeElement($elementsArtistique);

        return $this;
    }

    /**
     * @return Collection<int, Engagement>
     */
    public function getEngagements(): Collection
    {
        return $this->engagements;
    }

    public function addEngagement(Engagement $engagement): static
    {
        if (!$this->engagements->contains($engagement)) {
            $this->engagements->add($engagement);
            $engagement->setEpreuve($this);
        }

        return $this;
    }

    public function removeEngagement(Engagement $engagement): static
    {
        if ($this->engagements->removeElement($engagement)) {
            // set the owning side to null (unless already changed)
            if ($engagement->getEpreuve() === $this) {
                $engagement->setEpreuve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NoteTechnique>
     */
    public function getNoteTechniques(): Collection
    {
        return $this->noteTechniques;
    }

    public function addNoteTechnique(NoteTechnique $noteTechnique): static
    {
        if (!$this->noteTechniques->contains($noteTechnique)) {
            $this->noteTechniques->add($noteTechnique);
            $noteTechnique->setEpreuve($this);
        }

        return $this;
    }

    public function removeNoteTechnique(NoteTechnique $noteTechnique): static
    {
        if ($this->noteTechniques->removeElement($noteTechnique)) {
            // set the owning side to null (unless already changed)
            if ($noteTechnique->getEpreuve() === $this) {
                $noteTechnique->setEpreuve(null);
            }
        }

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
            $noteArtistique->setEpreuve($this);
        }

        return $this;
    }

    public function removeNoteArtistique(NoteArtistique $noteArtistique): static
    {
        if ($this->noteArtistiques->removeElement($noteArtistique)) {
            // set the owning side to null (unless already changed)
            if ($noteArtistique->getEpreuve() === $this) {
                $noteArtistique->setEpreuve(null);
            }
        }

        return $this;
    }

    public function getCompetition(): ?Competition
    {
        return $this->competition;
    }

    public function setCompetition(?Competition $competition): static
    {
        $this->competition = $competition;

        return $this;
    }
}
