<?php

namespace App\Controller;

use App\Entity\Epreuve;
use App\Entity\Patineuse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/saisie-notes')]
class SaisieNotesController extends AbstractController
{
    #[Route('/{patineuse}/{epreuve}', name: 'app_saisie_notes', methods: ['GET', 'POST'])]
    public function saisie(
        Patineuse $patineuse,
        Epreuve $epreuve,
    ): Response {
        return $this->render('saisie_notes/index.html.twig', [
            'patineuse' => $patineuse,
            'epreuve' => $epreuve,
        ]);
    }
}
