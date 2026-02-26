<?php

namespace App\Twig\Components;

use App\Entity\Competition;
use App\Entity\Epreuve;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveResponder;

#[AsLiveComponent]
class EpreuveListComponent extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Competition $competition;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LiveResponder $liveResponder,
    ) {
    }

    #[LiveListener('epreuve_added')]
    public function onEpreuveAdded(): void
    {
        $this->entityManager->refresh($this->competition);
    }

    #[LiveAction]
    public function delete(#[LiveArg] Epreuve $epreuve): void
    {
        if ($epreuve->getCompetition() === $this->competition) {
            // Vérifier s'il y a des engagements
            if ($epreuve->getEngagements()->count() > 0) {
                $this->addFlash('error', 'Impossible de supprimer une épreuve qui a des engagements.');

                return;
            }

            $this->entityManager->remove($epreuve);
            $this->entityManager->flush();
            $this->addFlash('success', 'L\'épreuve a été supprimée.');
        }
    }

    #[LiveAction]
    public function edit(#[LiveArg] Epreuve $epreuve): void
    {
        if ($epreuve->getCompetition() === $this->competition) {
            // Demander au formulaire de charger cette épreuve pour édition
            $this->liveResponder->emit('edit_epreuve', ['epreuve' => $epreuve->getId()]);
        }
    }
}
