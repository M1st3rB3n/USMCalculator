<?php

namespace App\Twig\Components;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class CategorieFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public ?Categorie $initialFormData = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CategorieType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function save(): ?Response
    {
        $this->submitForm();

        if (!$this->getForm()->isValid()) {
            return null;
        }

        /** @var Categorie $categorie */
        $categorie = $this->getForm()->getData();

        try {
            $this->entityManager->persist($categorie);
            $this->entityManager->flush();

            $this->addFlash('success', 'La catégorie a été enregistrée avec succès.');

            return $this->redirectToRoute('app_categorie_index');
        } catch (\Exception $e) {
            // Handle error (e.g., logging)
        }

        return null;
    }
}
