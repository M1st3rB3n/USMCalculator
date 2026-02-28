<?php

namespace App\Twig\Components;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\PatineuseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class CategorieListComponent extends AbstractController
{
    use DefaultActionTrait;

    public function __construct(
        private readonly CategorieRepository $categorieRepository,
        private readonly PatineuseRepository $patineuseRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<int, array{categorie: Categorie, patineuse_count: int}>
     */
    public function getCategoriesWithCounts(): array
    {
        $categories = $this->categorieRepository->findAll();
        $data = [];

        foreach ($categories as $categorie) {
            $annees = [];
            foreach ($categorie->getAnnees() as $annee) {
                $annees[] = $annee->getAnnee();
            }
            $data[] = [
                'categorie' => $categorie,
                'patineuse_count' => $this->patineuseRepository->countByAnnees($annees),
            ];
        }

        return $data;
    }

    #[LiveAction]
    public function delete(#[LiveArg] Categorie $categorie): void
    {
        $this->entityManager->remove($categorie);
        $this->entityManager->flush();
    }
}
