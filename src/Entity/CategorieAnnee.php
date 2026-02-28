<?php

namespace App\Entity;

use App\Repository\AnneeNaissanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnneeNaissanceRepository::class)]
#[UniqueEntity(fields: ['annee'], message: 'Cette année est déjà associée à une autre catégorie.')]
class CategorieAnnee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    #[Assert\NotBlank(message: 'L\'année ne peut pas être vide.')]
    #[Assert\Positive(message: 'L\'année doit être positive.')]
    private ?int $annee = null;

    #[ORM\ManyToOne(inversedBy: 'annees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

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
}
