<?php

namespace App\Entity;

use App\Repository\ElementTechniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElementTechniqueRepository::class)]
class ElementTechnique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $famille = null;

    #[ORM\Column(nullable: true)]
    private ?float $score = null;

    #[ORM\Column(nullable: true)]
    private ?float $QoE = null;

    #[ORM\OneToMany(targetEntity: NoteTechnique::class, mappedBy: 'elementTechnique', orphanRemoval: true)]
    private Collection $noteTechniques;

    public function __construct()
    {
        $this->noteTechniques = new ArrayCollection();
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

    public function getFamille(): ?string
    {
        return $this->famille;
    }

    public function setFamille(?string $famille): static
    {
        $this->famille = $famille;

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
            $noteTechnique->setElementTechnique($this);
        }

        return $this;
    }

    public function removeNoteTechnique(NoteTechnique $noteTechnique): static
    {
        if ($this->noteTechniques->removeElement($noteTechnique)) {
            // set the owning side to null (unless already changed)
            if ($noteTechnique->getElementTechnique() === $this) {
                $noteTechnique->setElementTechnique(null);
            }
        }

        return $this;
    }
}
