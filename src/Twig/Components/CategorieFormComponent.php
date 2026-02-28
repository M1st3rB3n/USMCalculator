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
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent]
class CategorieFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;
    use LiveCollectionTrait;

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

        // Double check for duplicate years within the SAME category manually
        // because UniqueEntity('annee') only checks against other categories in DB
        $annees = [];
        foreach ($categorie->getAnnees() as $anneeEntity) {
            $val = $anneeEntity->getAnnee();
            if (null === $val) {
                continue;
            }
            if (in_array($val, $annees)) {
                $this->addFlash('error', 'Vous avez saisi deux fois la même année.');

                return null;
            }
            $annees[] = $val;
        }

        try {
            $this->entityManager->persist($categorie);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement. Vérifiez que les années ne sont pas déjà utilisées par une autre catégorie.');

            return null;
        }

        $this->addFlash('success', 'La catégorie a été enregistrée avec succès.');

        return $this->redirectToRoute('app_categorie_index');
    }
}
