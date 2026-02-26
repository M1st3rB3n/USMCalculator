<?php

namespace App\Controller;

use App\Entity\ElementTechnique;
use App\Form\ElementTechniqueType;
use App\Repository\ElementTechniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/element-technique')]
class ElementTechniqueController extends AbstractController
{
    #[Route('/', name: 'app_element_technique_index', methods: ['GET'])]
    public function index(ElementTechniqueRepository $repository): Response
    {
        return $this->render('element_technique/index.html.twig', [
            'elements' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_element_technique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $element = new ElementTechnique();
        $form = $this->createForm(ElementTechniqueType::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($element);
            $entityManager->flush();

            $this->addFlash('success', 'L\'élément technique a bien été créé.');

            return $this->redirectToRoute('app_element_technique_index');
        }

        return $this->render('element_technique/new.html.twig', [
            'element' => $element,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_element_technique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ElementTechnique $element, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ElementTechniqueType::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'élément technique a bien été modifié.');

            return $this->redirectToRoute('app_element_technique_index');
        }

        return $this->render('element_technique/edit.html.twig', [
            'element' => $element,
            'form' => $form,
        ]);
    }
}
