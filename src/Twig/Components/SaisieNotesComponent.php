<?php

namespace App\Twig\Components;

use App\Entity\Epreuve;
use App\Entity\NoteArtistique;
use App\Entity\NoteTechnique;
use App\Entity\Patineuse;
use App\Form\NoteArtistiqueType;
use App\Form\NoteTechniqueType;
use App\Repository\NoteArtistiqueRepository;
use App\Repository\NoteTechniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class SaisieNotesComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public Patineuse $patineuse;

    #[LiveProp]
    public Epreuve $epreuve;

    public function __construct(
        private readonly NoteTechniqueRepository $ntRepo,
        private readonly NoteArtistiqueRepository $naRepo,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        // On récupère les notes existantes ou on en crée de nouvelles
        $notesTechniques = [];
        foreach ($this->epreuve->getElementsTechniques() as $element) {
            $note = $this->ntRepo->findOneBy([
                'patineuse' => $this->patineuse,
                'epreuve' => $this->epreuve,
                'elementTechnique' => $element,
            ]);

            if (!$note) {
                $note = new NoteTechnique();
                $note->setPatineuse($this->patineuse);
                $note->setEpreuve($this->epreuve);
                $note->setElementTechnique($element);
                $note->setNote(null);
            }
            $notesTechniques[] = $note;
        }

        $notesArtistiques = [];
        foreach ($this->epreuve->getElementsArtistiques() as $element) {
            $note = $this->naRepo->findOneBy([
                'patineuse' => $this->patineuse,
                'epreuve' => $this->epreuve,
                'elementArtistique' => $element,
            ]);

            if (!$note) {
                $note = new NoteArtistique();
                $note->setPatineuse($this->patineuse);
                $note->setEpreuve($this->epreuve);
                $note->setElementArtistique($element);
                $note->setNote(null);
            }
            $notesArtistiques[] = $note;
        }

        return $this->createFormBuilder(['techniques' => $notesTechniques, 'artistiques' => $notesArtistiques])
            ->add('techniques', CollectionType::class, [
                'entry_type' => NoteTechniqueType::class,
                'entry_options' => ['label' => false],
            ])
            ->add('artistiques', CollectionType::class, [
                'entry_type' => NoteArtistiqueType::class,
                'entry_options' => ['label' => false],
            ])
            ->getForm();
    }

    #[LiveAction]
    public function save(): ?RedirectResponse
    {
        $this->submitForm();

        if ($this->getForm()->isValid()) {
            $data = $this->getForm()->getData();
            foreach ($data['techniques'] as $note) {
                $this->entityManager->persist($note);
            }
            foreach ($data['artistiques'] as $note) {
                $this->entityManager->persist($note);
            }
            $this->entityManager->flush();

            $this->addFlash('success', 'Les notes ont été enregistrées avec succès.');

            return $this->redirectToRoute('app_competition_engagements', [
                'id' => $this->epreuve->getCompetition()->getId(),
            ]);
        }

        return null;
    }

    private function getDataModelValue(): ?string
    {
        return 'norender|*';
    }
}
