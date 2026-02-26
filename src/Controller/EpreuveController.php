<?php

namespace App\Controller;

use App\Entity\Epreuve;
use App\Form\EpreuveType;
use App\Repository\EpreuveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/epreuve')]
class EpreuveController extends AbstractController
{
    #[Route('/', name: 'app_epreuve_index', methods: ['GET'])]
    public function index(EpreuveRepository $epreuveRepository): Response
    {
        return $this->render('epreuve/index.html.twig', [
            'epreuves' => $epreuveRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_epreuve_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $epreuve = new Epreuve();
        $form = $this->createForm(EpreuveType::class, $epreuve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($epreuve);
            $entityManager->flush();

            $this->addFlash('success', 'L\'épreuve a bien été créée.');

            return $this->redirectToRoute('app_epreuve_index');
        }

        return $this->render('epreuve/new.html.twig', [
            'epreuve' => $epreuve,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_epreuve_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Epreuve $epreuve, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EpreuveType::class, $epreuve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'épreuve a bien été modifiée.');

            return $this->redirectToRoute('app_epreuve_index');
        }

        return $this->render('epreuve/edit.html.twig', [
            'epreuve' => $epreuve,
            'form' => $form,
        ]);
    }
}
