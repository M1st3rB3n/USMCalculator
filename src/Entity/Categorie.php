<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(targetEntity: CategorieAnnee::class, mappedBy: 'categorie', cascade: ['persist', 'remove'])]
    private Collection $annees;

    #[ORM\OneToMany(targetEntity: Epreuve::class, mappedBy: 'categorie')]
    private Collection $epreuves;

    public function __construct()
    {
        $this->annees = new ArrayCollection();
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

    public function getNomComplet(): string
    {
        $annees = $this->annees->map(fn (CategorieAnnee $annee) => $annee->getAnnee())->toArray();
        if (empty($annees)) {
            return $this->nom;
        }
        sort($annees);

        return sprintf('%s (%s)', $this->nom, implode(', ', $annees));
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, CategorieAnnee>
     */
    public function getAnnees(): Collection
    {
        return $this->annees;
    }

    public function addAnnee(CategorieAnnee $annee): static
    {
        if (!$this->annees->contains($annee)) {
            $this->annees->add($annee);
            $annee->setCategorie($this);
        }

        return $this;
    }

    public function removeAnnee(CategorieAnnee $annee): static
    {
        if ($this->annees->removeElement($annee)) {
            // set the owning side to null (unless already changed)
            if ($annee->getCategorie() === $this) {
                $annee->setCategorie(null);
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
            $epreuve->setCategorie($this);
        }

        return $this;
    }

    public function removeEpreuve(Epreuve $epreuve): static
    {
        if ($this->epreuves->removeElement($epreuve)) {
            // set the owning side to null (unless already changed)
            if ($epreuve->getCategorie() === $this) {
                $epreuve->setCategorie(null);
            }
        }

        return $this;
    }
}
