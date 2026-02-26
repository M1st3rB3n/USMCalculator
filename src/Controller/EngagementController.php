<?php

namespace App\Controller;

use App\Entity\Engagement;
use App\Form\EngagementType;
use App\Repository\EngagementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/engagement')]
class EngagementController extends AbstractController
{
    #[Route('/', name: 'app_engagement_index', methods: ['GET'])]
    public function index(EngagementRepository $engagementRepository): Response
    {
        return $this->render('engagement/index.html.twig', [
            'engagements' => $engagementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_engagement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $engagement = new Engagement();
        $form = $this->createForm(EngagementType::class, $engagement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($engagement);
            $entityManager->flush();

            $this->addFlash('success', 'L\'engagement a bien été créé.');

            return $this->redirectToRoute('app_engagement_index');
        }

        return $this->render('engagement/new.html.twig', [
            'engagement' => $engagement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_engagement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Engagement $engagement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EngagementType::class, $engagement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'engagement a bien été modifié.');

            return $this->redirectToRoute('app_engagement_index');
        }

        return $this->render('engagement/edit.html.twig', [
            'engagement' => $engagement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_engagement_delete', methods: ['POST'])]
    public function delete(Request $request, Engagement $engagement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$engagement->getId(), $request->request->get('_token'))) {
            $competitionId = $engagement->getEpreuve()->getCompetition()->getId();
            $entityManager->remove($engagement);
            $entityManager->flush();
            $this->addFlash('success', 'L\'engagement a bien été supprimé.');

            return $this->redirectToRoute('app_competition_engagements', ['id' => $competitionId]);
        }

        return $this->redirectToRoute('app_engagement_index');
    }
}
