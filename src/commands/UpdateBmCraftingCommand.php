<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionMarket\BlackMarketCraftingService;
use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\AdvancedRepository\BlackMarketCraftingRepository;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\JournalRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'market:bmCrafting', description: 'Update Calculations for blackmarket/crafting')]
class UpdateBmCraftingCommand extends Command
{
    public function __construct(
        private readonly BlackMarketCraftingService $blackMarketCraftingService,
        private readonly BlackMarketCraftingRepository $blackMarketCraftingRepository,
        private readonly ItemRepository $itemRepository,
        private readonly ResourceRepository $resourceRepository,
        private readonly JournalRepository $journalRepository,
        private readonly ConfigService $configService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bmSells = $this->configService->getBlackMarketSells();
        $bonusResources = $this->resourceRepository->getBonusResources();

        $city = 'Fort Sterling';
        $output->writeln('Updating BlackMarketCrafting from ' . $city . '...');
        $this->updateCalculations($city, $bmSells, $bonusResources, $output);

        $city = 'Lymhurst';
        $output->writeln(PHP_EOL . 'Updating BlackMarketCrafting from ' . $city . '...');
        $this->updateCalculations($city, $bmSells, $bonusResources, $output);

        $city = 'Bridgewatch';
        $output->writeln(PHP_EOL . 'Updating BlackMarketCrafting from ' . $city . '...');
        $this->updateCalculations($city, $bmSells, $bonusResources, $output);

        $city = 'Martlock';
        $output->writeln(PHP_EOL . 'Updating BlackMarketCrafting from ' . $city . '...');
        $this->updateCalculations($city, $bmSells, $bonusResources, $output);

        $city = 'Thetford';
        $output->writeln(PHP_EOL . 'Updating BlackMarketCrafting from ' . $city . '...');
        $this->updateCalculations($city, $bmSells, $bonusResources, $output);

        $output->writeln(PHP_EOL . 'Done');
        return self::SUCCESS;
    }

    private function updateCalculations(
        string $city,
        array $bmSells,
        array $bonusResources,
        OutputInterface $output
    ): void {
        $items = $this->itemRepository->getBlackMarketItemsFromCity($city);
        $resources = $this->resourceRepository->getResourcesByCity($city);
        $journals = $this->journalRepository->getJournalsFromCity($city);

        $calculateEntityArray = [];
        /** @var ItemEntity $item */
        foreach ($items as $item) {
            $calculateEntityArray[] = new BlackMarketCraftingEntity($item);
        }
        $progressBar = ProgressBarService::getProgressBar($output, count($calculateEntityArray));

        /** @var BlackMarketCraftingEntity $bmcEntity */
        foreach ($calculateEntityArray as $bmcEntity) {
            $message = sprintf('Update bmcEntity: %s from %s', $bmcEntity->getItem()->getRealName(), $city);
            $progressBar->setMessage($message);
            $progressBar->advance();
            $progressBar->display();

            $bmcEntityResources = $this->blackMarketCraftingService->calculateBmcEntity(
                $bmcEntity,
                $resources,
                $journals,
                $bmSells,
                $city
            );
            $this->blackMarketCraftingRepository->createOrUpdate($bmcEntityResources);
            $bmcEntityBonusResources = $this->blackMarketCraftingService->calculateBmcEntity(
                $bmcEntity,
                $bonusResources,
                $journals,
                $bmSells,
                $city
            );
            $bmcEntityBonusResources->setBonusResource(true);
            $this->blackMarketCraftingRepository->createOrUpdate($bmcEntityBonusResources);
        }
        $progressBar->setMessage('Update in ' . $city . ' finished');
        $progressBar->finish();
    }
}
