<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\Service\UploadService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'update', description: 'update Prices')]
class UpdatePricesCommand extends Command
{
    public function __construct(
        private UploadService $uploadService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'Succesful updated all Prices';
        try {
            $this->uploadService->updateItemPricesInAlbionDb($output);
            $this->uploadService->updateJournalPricesInAlbionDb($output);
            $this->uploadService->updateResourcePricesInAlbionDb($output);
        } catch (\JsonException|\RuntimeException $exception ) {
            $message .= ' Except for ' . $exception->getMessage();
        }
        $output->writeln($message);
        return self::SUCCESS;
    }

    protected function configure()
    {
        $this->setName('update');
        $this->setDescription('update Prices');
        $this->setHelp('updates Prices');
    }
}