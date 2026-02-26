<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Form\NiveauType;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/niveau')]
class NiveauController extends AbstractController
{
    #[Route('/', name: 'app_niveau_index', methods: ['GET'])]
    public function index(NiveauRepository $niveauRepository): Response
    {
        return $this->render('niveau/index.html.twig', [
            'niveaux' => $niveauRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_niveau_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $niveau = new Niveau();
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($niveau);
            $entityManager->flush();

            $this->addFlash('success', 'Le niveau a été créé avec succès.');

            return $this->redirectToRoute('app_niveau_index');
        }

        return $this->render('niveau/new.html.twig', [
            'niveau' => $niveau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_niveau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Niveau $niveau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le niveau a été modifié avec succès.');

            return $this->redirectToRoute('app_niveau_index');
        }

        return $this->render('niveau/edit.html.twig', [
            'niveau' => $niveau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_niveau_delete', methods: ['POST'])]
    public function delete(Request $request, Niveau $niveau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$niveau->getId(), $request->request->get('_token'))) {
            $entityManager->remove($niveau);
            $entityManager->flush();
            $this->addFlash('success', 'Le niveau a été supprimé.');
        }

        return $this->redirectToRoute('app_niveau_index');
    }
}
