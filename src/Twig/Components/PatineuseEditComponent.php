<?php

namespace App\Twig\Components;

use App\Entity\Patineuse;
use App\Form\PatineuseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class PatineuseEditComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(fieldName: 'patineuse_data')]
    public ?Patineuse $patineuse = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(PatineuseType::class, $this->patineuse);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): ?RedirectResponse
    {
        $this->submitForm();

        if ($this->getForm()->isValid()) {
            $isNew = null === $this->patineuse;
            if ($isNew) {
                $entityManager->persist($this->patineuse);
            }
            $entityManager->flush();

            if ($isNew) {
                $this->addFlash('success', 'La patineuse a bien été ajoutée.');
            } else {
                $this->addFlash('success', 'La patineuse a bien été modifiée.');
            }

            return $this->redirectToRoute('app_patineuse_index');
        }

        return null;
    }
}
