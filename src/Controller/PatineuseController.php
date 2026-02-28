<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Patineuse;
use App\Form\PatineuseImportType;
use App\Repository\ClubRepository;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PatineuseController extends AbstractController
{
    #[Route('/patineuses', name: 'app_patineuse_index')]
    public function index(Request $request, EntityManagerInterface $entityManager, ClubRepository $clubRepository, NiveauRepository $niveauRepository): Response
    {
        $form = $this->createForm(PatineuseImportType::class);
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
                        // Extraire l'ID du Google Sheet
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
                    $clubsCache = [];
                    $niveauxCache = [];

                    foreach ($rows as $row) {
                        if (count($row) < 5) {
                            continue;
                        }

                        $nom = $row[0];
                        $prenom = $row[1];
                        $clubNom = trim($row[2] ?? '');
                        $annee = $row[3];
                        $niveauNom = trim($row[4] ?? '');

                        if (!$nom || !$prenom) {
                            continue;
                        }

                        $patineuse = new Patineuse();
                        $patineuse->setNom($nom);
                        $patineuse->setPrenom($prenom);
                        $patineuse->setAnneeDeNaissance((int) $annee);

                        // Gestion du club
                        if ($clubNom !== '') {
                            if (isset($clubsCache[$clubNom])) {
                                $club = $clubsCache[$clubNom];
                            } else {
                                $club = $clubRepository->findOneBy(['nom' => $clubNom]);
                                if (!$club) {
                                    $club = new Club();
                                    $club->setNom($clubNom);
                                    $entityManager->persist($club);
                                }
                                $clubsCache[$clubNom] = $club;
                            }
                            $patineuse->setClub($club);
                        }

                        // Gestion du niveau
                        if ($niveauNom !== '') {
                            if (isset($niveauxCache[$niveauNom])) {
                                $niveau = $niveauxCache[$niveauNom];
                            } else {
                                $niveau = $niveauRepository->findOneBy(['nom' => $niveauNom]);
                                if ($niveau) {
                                    $niveauxCache[$niveauNom] = $niveau;
                                }
                            }

                            if (isset($niveau)) {
                                $patineuse->setNiveau($niveau);
                                unset($niveau);
                            }
                        }

                        $entityManager->persist($patineuse);
                        ++$importedCount;
                    }

                    $entityManager->flush();
                    $this->addFlash('success', sprintf('%d patineuses importées avec succès.', $importedCount));

                    return $this->redirectToRoute('app_patineuse_index');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'importation : '.$e->getMessage());
                }
            }
        }

        return $this->render('patineuse/index.html.twig', [
            'importForm' => $form->createView(),
        ]);
    }

    #[Route('/patineuse/new', name: 'app_patineuse_new')]
    public function new(): Response
    {
        return $this->render('patineuse/new.html.twig', [
            'patineuse' => new Patineuse(),
        ]);
    }

    #[Route('/patineuse/{id}/edit', name: 'app_patineuse_edit')]
    public function edit(Patineuse $patineuse): Response
    {
        return $this->render('patineuse/edit.html.twig', [
            'patineuse' => $patineuse,
        ]);
    }

    #[Route('/patineuse/{id}', name: 'app_patineuse_delete', methods: ['POST'])]
    public function delete(Request $request, Patineuse $patineuse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$patineuse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($patineuse);
            $entityManager->flush();
            $this->addFlash('success', 'Patineuse supprimée avec succès.');
        }

        return $this->redirectToRoute('app_patineuse_index', [], Response::HTTP_SEE_OTHER);
    }
}
