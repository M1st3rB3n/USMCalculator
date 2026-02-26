<?php

namespace App\Twig\Components;

use App\Entity\Competition;
use App\Entity\Engagement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class EngagementListComponent extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Competition $competition;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[LiveListener('engagement_added')]
    public function onEngagementAdded(): void
    {
        $this->entityManager->refresh($this->competition);
    }

    #[LiveAction]
    public function delete(#[LiveArg] Engagement $engagement): void
    {
        // Vérifier que l'engagement appartient bien à cette compétition pour plus de sécurité
        if ($engagement->getEpreuve()->getCompetition() === $this->competition) {
            $this->entityManager->remove($engagement);
            $this->entityManager->flush();
        }
    }

    public function getEngagementsFound(): bool
    {
        foreach ($this->competition->getEpreuves() as $epreuve) {
            if ($epreuve->getEngagements()->count() > 0) {
                return true;
            }
        }

        return false;
    }
}
