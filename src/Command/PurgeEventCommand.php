<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:purge-event',
    description: 'Delete events and tools which are finished for 6 months and more'
)]
class PurgeEventCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Filesystem $filesystem,
        #[Autowire('%kernel.project_dir%')] private string $projectDir
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $output->writeln('Getting old events ...');
        $now = new \DateTime();
        $interval = new \DateInterval('P6M');
        $startDate = $now->sub($interval);
        $oldEvents = $this->entityManager->getRepository(Event::class)->findAllOlderThanDate($startDate);

        if($oldEvents) {
            $output->writeln('Purging events ...');

            foreach($oldEvents as $event) {
                $output->writeln('Event '.$event->getName().' : purging tools ...');

                foreach($event->getTools() as $tool) {
                    $this->entityManager->remove($tool);
                }

                // Remove folder with signatures
                $this->filesystem->remove($this->projectDir.'/public/uploads/signature/'.$event->getId());

                $this->entityManager->remove($event);
            }

            $this->entityManager->flush();
        }
        else {
            $output->writeln('No event to purge !');
        }

        // Delete temp users : truncate table
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('user_temp'));

        $io->success('Events successfully purged !');

        return Command::SUCCESS;
    }
}