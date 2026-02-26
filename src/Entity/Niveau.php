<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NiveauRepository::class)]
class Niveau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(targetEntity: Patineuse::class, mappedBy: 'niveau')]
    private Collection $patineuses;

    #[ORM\OneToMany(targetEntity: Epreuve::class, mappedBy: 'niveau')]
    private Collection $epreuves;

    public function __construct()
    {
        $this->patineuses = new ArrayCollection();
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

    /**
     * @return Collection<int, Patineuse>
     */
    public function getPatineuses(): Collection
    {
        return $this->patineuses;
    }

    public function addPatineuse(Patineuse $patineuse): static
    {
        if (!$this->patineuses->contains($patineuse)) {
            $this->patineuses->add($patineuse);
            $patineuse->setNiveau($this);
        }

        return $this;
    }

    public function removePatineuse(Patineuse $patineuse): static
    {
        if ($this->patineuses->removeElement($patineuse)) {
            // set the owning side to null (unless already changed)
            if ($patineuse->getNiveau() === $this) {
                $patineuse->setNiveau(null);
            }
        }

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
            $epreuve->setNiveau($this);
        }

        return $this;
    }

    public function removeEpreuve(Epreuve $epreuve): static
    {
        if ($this->epreuves->removeElement($epreuve)) {
            // set the owning side to null (unless already changed)
            if ($epreuve->getNiveau() === $this) {
                $epreuve->setNiveau(null);
            }
        }

        return $this;
    }
}
