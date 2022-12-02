<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\repositories\JournalRepository;
use MZierdt\Albion\repositories\UploadRepository;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use MZierdt\Albion\Service\UploadHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateJournalsCommand extends Command
{
    public function __construct(
        private ApiService $apiService,
        private JournalRepository $journalRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'successfully updated all Prices';

        try {
            $journalList = ConfigService::getJournalConfig();
        } catch (\JsonException $jsonException) {
            $output->writeln($jsonException->getMessage());
            return self::FAILURE;
        }
        $progressBar = ProgressBarService::getProgressBar(
            $output,
            is_countable($journalList['names']) ? count($journalList['names']) : 0
        );

        foreach ($journalList['names'] as $journalNames) {
            $progressBar->setMessage('Get Resource ' . $journalNames);
            $progressBar->advance();
            $progressBar->display();
            $journalsData = $this->apiService->getJournals($journalNames);
            $progressBar->setMessage('preparing resource ' . $journalNames);
            $progressBar->display();
            $adjustedJournals = UploadHelper::adjustJournals($journalsData, $journalList['stats']);
            $progressBar->setMessage('Upload Resource ' . $journalNames . ' into Database');
            $progressBar->display();
            $this->journalRepository->updatePricesFromJournals($adjustedJournals);
        }

        $output->writeln(PHP_EOL . $message);
        return self::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('update:journal');
        $this->setDescription('update Prices of Journals');
        $this->setHelp('updates Prices of Journal');
    }
}
