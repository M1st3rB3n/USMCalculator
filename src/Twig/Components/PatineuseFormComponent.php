<?php

namespace App\Twig\Components;

use App\Entity\Patineuse;
use App\Form\PatineuseType;
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
class PatineuseFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public ?Patineuse $initialFormData = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(PatineuseType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function save(): void
    {
        $this->submitForm();

        if (!$this->getForm()->isValid()) {
            return;
        }

        /** @var Patineuse $patineuse */
        $patineuse = $this->getForm()->getData();

        try {
            $this->entityManager->persist($patineuse);
            $this->entityManager->flush();

            $this->emit('patineuse_added');

            $this->resetForm();
        } catch (\Exception $e) {
        }
    }

    private function getDataModelValue(): ?string
    {
        return 'norender|*';
    }
}
