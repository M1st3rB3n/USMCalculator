<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Form\CompetitionType;
use App\Repository\CompetitionRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/competition')]
class CompetitionController extends AbstractController
{
    #[Route('/', name: 'app_competition_index', methods: ['GET'])]
    public function index(CompetitionRepository $competitionRepository): Response
    {
        return $this->render('competition/index.html.twig', [
            'competitions' => $competitionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_competition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $competition = new Competition();
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($competition);
            $entityManager->flush();

            $this->addFlash('success', 'La compétition a été créée avec succès.');

            return $this->redirectToRoute('app_competition_index');
        }

        return $this->render('competition/new.html.twig', [
            'competition' => $competition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_competition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Competition $competition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La compétition a été modifiée avec succès.');

            return $this->redirectToRoute('app_competition_index');
        }

        return $this->render('competition/edit.html.twig', [
            'competition' => $competition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/resultats', name: 'app_competition_resultats', methods: ['GET'])]
    public function resultats(Competition $competition): Response
    {
        $clubStats = $this->getClubStats($competition);

        return $this->render('competition/resultats.html.twig', [
            'competition' => $competition,
            'clubStats' => $clubStats,
        ]);
    }

    #[Route('/{id}/resultats/pdf', name: 'app_competition_resultats_pdf', methods: ['GET'])]
    public function pdfResultats(Competition $competition): Response
    {
        $clubStats = $this->getClubStats($competition);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('competition/pdf_resultats.html.twig', [
            'competition' => $competition,
            'clubStats' => $clubStats,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $filename = sprintf('resultats_%s.pdf', str_replace(' ', '_', $competition->getNom()));

        return new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('inline; filename="%s"', $filename),
        ]);
    }

    private function getClubStats(Competition $competition): array
    {
        $clubStats = [];

        foreach ($competition->getEpreuves() as $epreuve) {
            foreach ($epreuve->getEngagements() as $engagement) {
                $club = $engagement->getPatineuse()->getClub();
                if (!$club) {
                    continue;
                }

                $clubId = $club->getId();
                if (!isset($clubStats[$clubId])) {
                    $clubStats[$clubId] = [
                        'club' => $club,
                        'totalPoints' => 0,
                        'nombreEngagements' => 0,
                    ];
                }

                $totalNotes = $engagement->getTotalNotes();
                if ($totalNotes <= 0) {
                    continue;
                }

                $clubStats[$clubId]['totalPoints'] += $totalNotes;
                ++$clubStats[$clubId]['nombreEngagements'];
            }
        }

        // Calculer la moyenne (pondérée par le nombre d'engagements)
        foreach ($clubStats as $id => $stats) {
            $clubStats[$id]['moyenne'] = $stats['nombreEngagements'] > 0
                ? $stats['totalPoints'] / $stats['nombreEngagements']
                : 0;
        }

        // Trier par moyenne décroissante
        uasort($clubStats, fn ($a, $b) => $b['moyenne'] <=> $a['moyenne']);

        return $clubStats;
    }

    #[Route('/{id}/engagements', name: 'app_competition_engagements', methods: ['GET'])]
    public function engagements(Competition $competition): Response
    {
        return $this->render('competition/engagements.html.twig', [
            'competition' => $competition,
        ]);
    }

    #[Route('/{id}/epreuves', name: 'app_competition_epreuves', methods: ['GET'])]
    public function epreuves(Competition $competition): Response
    {
        return $this->render('competition/epreuves.html.twig', [
            'competition' => $competition,
        ]);
    }
}
