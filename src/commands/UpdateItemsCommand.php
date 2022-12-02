<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\UploadRepository;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use MZierdt\Albion\Service\UploadHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateItemsCommand extends Command
{
    public function __construct(
        private ApiService $apiService,
        private ItemRepository $itemRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'successfully updated all Prices';
        try {
            $itemList = ConfigService::getItemConfig();
        } catch (\JsonException $jsonException) {
            $output->writeln($jsonException->getMessage());
            return self::FAILURE;
        }

        $progressBar = ProgressBarService::getProgressBar($output, is_countable($itemList) ? count($itemList) : 0);

        foreach ($itemList as $itemStats) {
            $progressBar->setMessage('Get Item:' . $itemStats['realName']);
            $progressBar->advance();
            $progressBar->display();
            $itemsData = $this->apiService->getItems($itemStats['id_snippet']);
            $progressBar->setMessage('preparing Item' . $itemStats['realName']);
            $progressBar->display();
            $adjustedItems = UploadHelper::adjustItems($itemsData, $itemStats);
            $progressBar->setMessage('Upload Item ' . $itemStats['realName'] . ' into Database');
            $progressBar->display();
            $this->itemRepository->updatePricesFromItem($adjustedItems);
        }

        $output->writeln(PHP_EOL . $message);
        return self::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('update:items');
        $this->setDescription('update Prices of Items');
        $this->setHelp('updates Prices of Items');
    }
}
