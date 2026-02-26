<?php

namespace App\Entity;

use App\Repository\PatineuseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatineuseRepository::class)]
class Patineuse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?int $anneeDeNaissance = null;

    #[ORM\ManyToOne(inversedBy: 'patineuses')]
    private ?Niveau $niveau = null;

    #[ORM\ManyToOne(inversedBy: 'patineuses')]
    private ?Club $club = null;

    #[ORM\OneToMany(targetEntity: Engagement::class, mappedBy: 'patineuse', orphanRemoval: true)]
    private Collection $engagements;

    #[ORM\OneToMany(targetEntity: NoteTechnique::class, mappedBy: 'patineuse', orphanRemoval: true)]
    private Collection $noteTechniques;

    #[ORM\OneToMany(targetEntity: NoteArtistique::class, mappedBy: 'patineuse', orphanRemoval: true)]
    private Collection $noteArtistiques;

    public function __construct()
    {
        $this->engagements = new ArrayCollection();
        $this->noteTechniques = new ArrayCollection();
        $this->noteArtistiques = new ArrayCollection();
    }

    /**
     * @return Collection<int, Engagement>
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEngagements(): Collection
    {
        return $this->engagements;
    }

    public function addEngagement(Engagement $engagement): static
    {
        if (!$this->engagements->contains($engagement)) {
            $this->engagements->add($engagement);
            $engagement->setPatineuse($this);
        }

        return $this;
    }

    public function removeEngagement(Engagement $engagement): static
    {
        if ($this->engagements->removeElement($engagement)) {
            // set the owning side to null (unless already changed)
            if ($engagement->getPatineuse() === $this) {
                $engagement->setPatineuse(null);
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
            $noteTechnique->setPatineuse($this);
        }

        return $this;
    }

    public function removeNoteTechnique(NoteTechnique $noteTechnique): static
    {
        if ($this->noteTechniques->removeElement($noteTechnique)) {
            // set the owning side to null (unless already changed)
            if ($noteTechnique->getPatineuse() === $this) {
                $noteTechnique->setPatineuse(null);
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
            $noteArtistique->setPatineuse($this);
        }

        return $this;
    }

    public function removeNoteArtistique(NoteArtistique $noteArtistique): static
    {
        if ($this->noteArtistiques->removeElement($noteArtistique)) {
            // set the owning side to null (unless already changed)
            if ($noteArtistique->getPatineuse() === $this) {
                $noteArtistique->setPatineuse(null);
            }
        }

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAnneeDeNaissance(): ?int
    {
        return $this->anneeDeNaissance;
    }

    public function setAnneeDeNaissance(int $anneeDeNaissance): static
    {
        $this->anneeDeNaissance = $anneeDeNaissance;

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

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): static
    {
        $this->club = $club;

        return $this;
    }

}
