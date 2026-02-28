<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:database:export',
    description: 'Exporte la base de données SQLite vers un fichier spécifique',
)]
class DatabaseExportCommand extends Command
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        private readonly Filesystem $filesystem,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('destination', InputArgument::OPTIONAL, 'Le chemin de destination du fichier exporté', 'var/data/export_data.db')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $destination = $input->getArgument('destination');

        // Si le chemin n'est pas absolu, on le considère relatif à la racine du projet
        if (!str_starts_with($destination, '/') && !str_contains($destination, ':')) {
            $destination = $this->projectDir.DIRECTORY_SEPARATOR.$destination;
        }

        $source = $this->projectDir.DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'data.db';

        if (!$this->filesystem->exists($source)) {
            $io->error(sprintf('La base de données source n\'existe pas : %s', $source));

            return Command::FAILURE;
        }

        try {
            $this->filesystem->copy($source, $destination, true);
            $io->success(sprintf('Base de données exportée avec succès vers : %s', $destination));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Erreur lors de l\'exportation : %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
