<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

#[Route('/admin/database')]
class DatabaseController extends AbstractController
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        private readonly Filesystem $filesystem
    ) {
    }

    #[Route('/export', name: 'app_database_export')]
    public function export(): Response
    {
        $databasePath = $this->projectDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'data.db';

        if (!$this->filesystem->exists($databasePath)) {
            $this->addFlash('error', 'La base de données n\'existe pas.');
            return $this->redirectToRoute('app_home');
        }

        $response = new BinaryFileResponse($databasePath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'backup_' . date('Y-m-d_His') . '.db'
        );

        return $response;
    }

    #[Route('/import', name: 'app_database_import', methods: ['GET', 'POST'])]
    public function import(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            /** @var UploadedFile $file */
            $file = $request->files->get('database_file');

            if ($file) {
                $targetPath = $this->projectDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'data.db';

                try {
                    // On fait une sauvegarde de l'actuelle au cas où
                    $backupPath = $this->projectDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'data.db.bak';
                    if ($this->filesystem->exists($targetPath)) {
                        $this->filesystem->copy($targetPath, $backupPath, true);
                    }

                    $file->move(
                        dirname($targetPath),
                        basename($targetPath)
                    );

                    $this->addFlash('success', 'La base de données a été importée avec succès.');
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
                }

                return $this->redirectToRoute('app_home');
            }

            $this->addFlash('error', 'Veuillez sélectionner un fichier.');
        }

        return $this->render('database/import.html.twig');
    }
}
