<?php

namespace App\Entity;

use App\Repository\NoteTechniqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteTechniqueRepository::class)]
class NoteTechnique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    #[ORM\ManyToOne(inversedBy: 'noteTechniques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patineuse $patineuse = null;

    #[ORM\ManyToOne(inversedBy: 'noteTechniques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ElementTechnique $elementTechnique = null;

    #[ORM\ManyToOne(inversedBy: 'noteTechniques')]
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

    public function getElementTechnique(): ?ElementTechnique
    {
        return $this->elementTechnique;
    }

    public function setElementTechnique(?ElementTechnique $elementTechnique): static
    {
        $this->elementTechnique = $elementTechnique;

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
