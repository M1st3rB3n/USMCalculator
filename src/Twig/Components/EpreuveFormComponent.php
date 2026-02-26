<?php

namespace App\Twig\Components;

use App\Entity\Competition;
use App\Entity\Epreuve;
use App\Form\EpreuveType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveResponder;

#[AsLiveComponent]
class EpreuveFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?Epreuve $initialFormData = null;

    #[LiveProp]
    public ?Competition $competition = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LiveResponder $liveResponder,
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        $epreuve = $this->initialFormData ?? new Epreuve();
        if ($this->competition) {
            $epreuve->setCompetition($this->competition);
        }

        return $this->createForm(EpreuveType::class, $epreuve);
    }

    #[LiveAction]
    public function save(): void
    {
        $this->submitForm();

        if (!$this->getForm()->isValid()) {
            return;
        }

        /** @var Epreuve $epreuve */
        $epreuve = $this->getForm()->getData();
        $isNew = null === $epreuve->getId();

        try {
            $this->entityManager->persist($epreuve);
            $this->entityManager->flush();

            // Émettre un événement pour notifier la liste qu'une épreuve a été ajoutée ou modifiée
            $this->liveResponder->emit('epreuve_added');

            if (!$isNew) {
                $this->liveResponder->emit('epreuve_updated');
            }

            // Réinitialiser le formulaire
            $this->initialFormData = null;
            $this->resetForm();
        } catch (\Exception $e) {
            // Optionnel : gérer l'erreur
        }
    }

    #[LiveListener('edit_epreuve')]
    public function editEpreuve(#[LiveArg] int $epreuve): void
    {
        $this->initialFormData = $this->entityManager->getRepository(Epreuve::class)->find($epreuve);
        $this->resetForm();
    }

    private function getDataModelValue(): ?string
    {
        return 'norender|*';
    }
}
