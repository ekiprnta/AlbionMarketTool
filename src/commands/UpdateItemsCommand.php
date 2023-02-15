<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionDataAPI\ItemApiService;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use MZierdt\Albion\Service\UploadHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateItemsCommand extends Command
{
    public function __construct(
        private readonly ItemApiService $itemApiService,
        private readonly ItemRepository $itemRepository,
        private readonly ConfigService $configService,
        private readonly UploadHelper $uploadHelper,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'successfully updated all Prices';
        try {
            $itemList = $this->configService->getItemConfig();
            $capesAndRoyalList = $this->configService->getCapesAndRoyalConfig();
        } catch (\JsonException $jsonException) {
            $output->writeln($jsonException->getMessage());
            return self::FAILURE;
        }

        $output->writeln('Updating Black Market Items...');
        $progressBar = ProgressBarService::getProgressBar($output, is_countable($itemList) ? count($itemList) : 0);

        foreach ($itemList as $itemStats) {
            $progressBar->setMessage('Get Item:' . $itemStats['realName']);
            $progressBar->advance();
            $progressBar->display();
            $capeAndRoyalData = $this->itemApiService->getItems($itemStats['id_snippet']);
            $progressBar->setMessage('preparing Item' . $itemStats['realName']);
            $progressBar->display();
            $adjustedCapes = $this->uploadHelper->adjustItems($capeAndRoyalData, $itemStats, true);
            $progressBar->setMessage('Upload Item ' . $itemStats['realName'] . ' into Database');
            $progressBar->display();
            foreach ($adjustedCapes as $adjustedItem) {
                $this->itemRepository->createOrUpdate($adjustedItem);
            }
        }

        $output->writeln(PHP_EOL . 'Updating Capes...');
        $progressBar = ProgressBarService::getProgressBar(
            $output,
            is_countable($capesAndRoyalList) ? count($capesAndRoyalList) : 0
        );

        foreach ($capesAndRoyalList as $capeAndRoyalStats) {
            $progressBar->setMessage('Get Cape:' . $capeAndRoyalStats['realName']);
            $progressBar->advance();
            $progressBar->display();
            $capeAndRoyalData = $this->itemApiService->getCapes($capeAndRoyalStats['id_snippet']);
            $progressBar->setMessage('preparing Cape' . $capeAndRoyalStats['realName']);
            $progressBar->display();
            $adjustedCapes = $this->uploadHelper->adjustItems($capeAndRoyalData, $capeAndRoyalStats, false);
            $progressBar->setMessage('Upload Cape ' . $capeAndRoyalStats['realName'] . ' into Database');
            $progressBar->display();
            /** @var ItemEntity $adjustedCapeAndRoyal */
            foreach ($adjustedCapes as $adjustedCapeAndRoyal) {
                if ($adjustedCapeAndRoyal->getTier() < 40) {
                    continue;
                }
                $adjustedCapeAndRoyal->setSecondaryResourceAmount(
                    $this->uploadHelper->calculateHeartAndSigilAmount(
                        $adjustedCapeAndRoyal->getTier(),
                        $adjustedCapeAndRoyal->getName()
                    )
                );
                $this->itemRepository->createOrUpdate($adjustedCapeAndRoyal);
            }
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
