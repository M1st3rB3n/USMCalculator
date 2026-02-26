<?php

namespace App\Twig\Components;

use App\Entity\Competition;
use App\Entity\Engagement;
use App\Form\EngagementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class EngagementFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public ?Engagement $initialFormData = null;

    #[LiveProp]
    public ?Competition $competition = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(EngagementType::class, $this->initialFormData, [
            'competition' => $this->competition,
        ]);
    }

    #[LiveAction]
    public function save(): void
    {
        $this->submitForm();

        if (!$this->getForm()->isValid()) {
            return;
        }

        /** @var Engagement $engagement */
        $engagement = $this->getForm()->getData();

        try {
            $this->entityManager->persist($engagement);
            $this->entityManager->flush();

            // Émettre un événement pour notifier la liste qu'un engagement a été ajouté
            $this->emit('engagement_added');

            // Réinitialiser le formulaire pour un nouvel engagement
            $this->resetForm();
        } catch (\Exception $e) {
        }
    }

    private function getDataModelValue(): ?string
    {
        return 'norender|*';
    }
}
