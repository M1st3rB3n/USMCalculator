<?php

namespace App\Controller;

use App\Entity\ElementArtistique;
use App\Form\ElementArtistiqueType;
use App\Repository\ElementArtistiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/element-artistique')]
class ElementArtistiqueController extends AbstractController
{
    #[Route('/', name: 'app_element_artistique_index', methods: ['GET'])]
    public function index(ElementArtistiqueRepository $repository): Response
    {
        return $this->render('element_artistique/index.html.twig', [
            'elements' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_element_artistique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $element = new ElementArtistique();
        $form = $this->createForm(ElementArtistiqueType::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($element);
            $entityManager->flush();

            $this->addFlash('success', 'L\'élément artistique a bien été créé.');

            return $this->redirectToRoute('app_element_artistique_index');
        }

        return $this->render('element_artistique/new.html.twig', [
            'element' => $element,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_element_artistique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ElementArtistique $element, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ElementArtistiqueType::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'élément artistique a bien été modifié.');

            return $this->redirectToRoute('app_element_artistique_index');
        }

        return $this->render('element_artistique/edit.html.twig', [
            'element' => $element,
            'form' => $form,
        ]);
    }
}
