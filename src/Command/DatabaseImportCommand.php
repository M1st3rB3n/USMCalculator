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
    name: 'app:database:import',
    description: 'Importe une base de données SQLite à partir d\'un fichier spécifique',
)]
class DatabaseImportCommand extends Command
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
            ->addArgument('source', InputArgument::REQUIRED, 'Le chemin du fichier source à importer')
            ->addArgument('confirm', InputArgument::OPTIONAL, 'Confirmer l\'écrasement automatique (yes/no)', 'no')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $source = $input->getArgument('source');
        $confirm = $input->getArgument('confirm');

        // Si le chemin n'est pas absolu, on le considère relatif à la racine du projet
        if (!str_starts_with($source, '/') && !str_contains($source, ':')) {
            $source = $this->projectDir.DIRECTORY_SEPARATOR.$source;
        }

        $destination = $this->projectDir.DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'data.db';

        if (!$this->filesystem->exists($source)) {
            $io->error(sprintf('Le fichier source n\'existe pas : %s', $source));

            return Command::FAILURE;
        }

        if ($this->filesystem->exists($destination) && 'yes' !== $confirm) {
            if (!$io->confirm('La base de données actuelle va être écrasée. Voulez-vous continuer ?', false)) {
                $io->info('Opération annulée.');

                return Command::SUCCESS;
            }
        }

        try {
            // S'assurer que le répertoire de destination existe
            $dir = dirname($destination);
            if (!$this->filesystem->exists($dir)) {
                $this->filesystem->mkdir($dir);
            }

            $this->filesystem->copy($source, $destination, true);
            $io->success(sprintf('Base de données importée avec succès depuis : %s', $source));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Erreur lors de l\'importation : %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
