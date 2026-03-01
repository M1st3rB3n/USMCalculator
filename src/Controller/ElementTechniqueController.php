<?php

namespace App\Controller;

use App\Entity\ElementTechnique;
use App\Form\ElementTechniqueImportType;
use App\Form\ElementTechniqueType;
use App\Repository\ElementTechniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/element-technique')]
class ElementTechniqueController extends AbstractController
{
    #[Route('/', name: 'app_element_technique_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ElementTechniqueRepository $repository): Response
    {
        $form = $this->createForm(ElementTechniqueImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $googleSheetUrl = $form->get('googleSheetUrl')->getData();

            if ($file || $googleSheetUrl) {
                try {
                    $spreadsheet = null;
                    if ($file) {
                        $spreadsheet = IOFactory::load($file->getPathname());
                    } elseif ($googleSheetUrl) {
                        if (preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $googleSheetUrl, $matches)) {
                            $sheetId = $matches[1];
                            $exportUrl = sprintf('https://docs.google.com/spreadsheets/d/%s/export?format=xlsx', $sheetId);

                            $content = file_get_contents($exportUrl);
                            if (false === $content) {
                                throw new \Exception("Impossible de télécharger le Google Sheet. Vérifiez qu'il est accessible avec le lien.");
                            }

                            $tempFile = tempnam(sys_get_temp_dir(), 'gsheet');
                            file_put_contents($tempFile, $content);

                            $spreadsheet = IOFactory::load($tempFile);
                            unlink($tempFile);
                        } else {
                            throw new \Exception('URL Google Sheet invalide.');
                        }
                    }

                    if (!$spreadsheet) {
                        throw new \Exception('Impossible de charger les données.');
                    }

                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = $sheet->toArray();

                    // Supposons que la première ligne est l'en-tête
                    $header = array_shift($rows);
                    $importedCount = 0;

                    foreach ($rows as $row) {
                        if (count($row) < 4) {
                            continue;
                        }

                        $nom = trim($row[0] ?? '');
                        $famille = trim($row[1] ?? '');
                        $score = $row[2] ?? null;
                        $qoe = $row[3] ?? null;

                        if (!$nom) {
                            continue;
                        }

                        $element = $repository->findOneBy(['nom' => $nom]);

                        if (!$element) {
                            $element = new ElementTechnique();
                            $element->setNom($nom);
                        }

                        $element->setFamille($famille ?: null);
                        $element->setScore(null !== $score ? (float) $score : null);
                        $element->setQoE(null !== $qoe ? (float) $qoe : null);

                        $entityManager->persist($element);
                        ++$importedCount;
                    }

                    $entityManager->flush();
                    $this->addFlash('success', sprintf('%d éléments techniques importés avec succès.', $importedCount));

                    return $this->redirectToRoute('app_element_technique_index');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'importation : '.$e->getMessage());
                }
            }
        }

        return $this->render('element_technique/index.html.twig', [
            'elements' => $repository->findAll(),
            'importForm' => $form->createView(),
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
