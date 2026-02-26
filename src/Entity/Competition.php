<?php

namespace App\Entity;

use App\Repository\CompetitionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompetitionRepository::class)]
class Competition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\OneToMany(targetEntity: Epreuve::class, mappedBy: 'competition')]
    private Collection $epreuves;

    public function __construct()
    {
        $this->epreuves = new ArrayCollection();
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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Epreuve>
     */
    public function getEpreuves(): Collection
    {
        return $this->epreuves;
    }

    public function addEpreuve(Epreuve $epreuve): static
    {
        if (!$this->epreuves->contains($epreuve)) {
            $this->epreuves->add($epreuve);
            $epreuve->setCompetition($this);
        }

        return $this;
    }

    public function removeEpreuve(Epreuve $epreuve): static
    {
        if ($this->epreuves->removeElement($epreuve)) {
            // set the owning side to null (unless already changed)
            if ($epreuve->getCompetition() === $this) {
                $epreuve->setCompetition(null);
            }
        }

        return $this;
    }
}
