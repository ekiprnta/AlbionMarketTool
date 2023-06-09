<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionDataAPI\MiscApiService;
use MZierdt\Albion\repositories\JournalRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use MZierdt\Albion\Service\UploadHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'update:journals', description: 'update Prices of Journals')]
class UpdateJournalsCommand extends Command
{
    public function __construct(
        private readonly MiscApiService $miscApiService,
        private readonly JournalRepository $journalRepository,
        private readonly ConfigService $configService,
        private readonly UploadHelper $uploadHelper,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'successfully updated all Prices';

        try {
            $journalList = $this->configService->getJournalConfig();
        } catch (\JsonException $jsonException) {
            $output->writeln($jsonException->getMessage());
            return self::FAILURE;
        }

        $output->writeln('Updating Journals...');
        $progressBar = ProgressBarService::getProgressBar(
            $output,
            is_countable($journalList['names']) ? count($journalList['names']) : 0
        );

        foreach ($journalList['names'] as $journalName) {
            $progressBar->setMessage('Get Resource ' . $journalName);
            $progressBar->advance();
            $progressBar->display();
            $journalsData = $this->miscApiService->getJournals($journalName);
            $progressBar->setMessage('preparing resource ' . $journalName);
            $progressBar->display();
            $adjustedJournals = $this->uploadHelper->adjustJournals($journalsData, $journalList['stats']);
            $progressBar->setMessage('Upload Resource ' . $journalName . ' into Database');
            $progressBar->display();
            foreach ($adjustedJournals as $adjustedJournal) {
                $this->journalRepository->createOrUpdate($adjustedJournal);
            }
        }

        $output->writeln(PHP_EOL . $message);
        return self::SUCCESS;
    }
}
