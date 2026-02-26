<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\OneToMany(targetEntity: Patineuse::class, mappedBy: 'club')]
    private Collection $patineuses;

    public function __construct()
    {
        $this->patineuses = new ArrayCollection();
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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

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
            $patineuse->setClub($this);
        }

        return $this;
    }

    public function removePatineuse(Patineuse $patineuse): static
    {
        if ($this->patineuses->removeElement($patineuse)) {
            // set the owning side to null (unless already changed)
            if ($patineuse->getClub() === $this) {
                $patineuse->setClub(null);
            }
        }

        return $this;
    }
}
