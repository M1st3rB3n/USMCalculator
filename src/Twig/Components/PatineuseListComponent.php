<?php

namespace App\Twig\Components;

use App\Entity\Niveau;
use App\Entity\Patineuse;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class PatineuseListComponent extends AbstractController
{
    use DefaultActionTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly NiveauRepository $niveauRepository,
    ) {
    }

    #[LiveListener('patineuse_added')]
    public function onPatineuseAdded(): void
    {
        // Pas besoin de faire grand chose ici, le re-rendu automatique suffira
    }

    #[LiveAction]
    public function delete(#[LiveArg] Patineuse $patineuse): void
    {
        $this->entityManager->remove($patineuse);
        $this->entityManager->flush();
    }

    /**
     * @return Niveau[]
     */
    public function getNiveaux(): array
    {
        return $this->niveauRepository->findAll();
    }
}
