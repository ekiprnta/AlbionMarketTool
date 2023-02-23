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
        private readonly ConfigService $configService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bmSells = $this->configService->getBlackMarketSells();

        $city = 'Fort Sterling';
        $output->writeln('Updating BlackMarketCrafting from ' . $city . '...');
        $this->updateCalculations($city, $bmSells, $output);

        $city = 'Lymhurst';
        $output->writeln(PHP_EOL . 'Updating BlackMarketCrafting from ' . $city . '...');
        $this->updateCalculations($city, $bmSells, $output);

        $city = 'Bridgewatch';
        $output->writeln(PHP_EOL . 'Updating BlackMarketCrafting from ' . $city . '...');
        $this->updateCalculations($city, $bmSells, $output);

        $city = 'Martlock';
        $output->writeln(PHP_EOL . 'Updating BlackMarketCrafting from ' . $city . '...');
        $this->updateCalculations($city, $bmSells, $output);

        $city = 'Thetford';
        $output->writeln(PHP_EOL . 'Updating BlackMarketCrafting from ' . $city . '...');
        $this->updateCalculations($city, $bmSells, $output);

        $output->writeln(PHP_EOL . 'Done');
        return self::SUCCESS;
    }

    private function updateCalculations(string $city, array $bmSells, OutputInterface $output): void
    {
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
            $itemEntity = $bmcEntity->getItem();
            $message = sprintf('Update bmcEntity: %s from %s', $itemEntity->getRealName(), $city);
            $progressBar->setMessage($message);
            $progressBar->advance();
            $progressBar->display();

            $bmcEntity->setPrimResource(
                $this->blackMarketCraftingService->calculateResource(
                    $itemEntity
                        ->getPrimaryResource(),
                    $itemEntity
                        ->getTier(),
                    $resources
                )
            );
            $bmcEntity->setSecResource(
                $this->blackMarketCraftingService->calculateResource(
                    $itemEntity
                        ->getSecondaryResource(),
                    $itemEntity
                        ->getTier(),
                    $resources
                )
            );
            $bmcEntity->setJournalEntityFull(
                $this->blackMarketCraftingService->calculateJournal($itemEntity->getTier(), 'full', $journals)
            );
            $bmcEntity->setJournalEntityEmpty(
                $this->blackMarketCraftingService->calculateJournal($itemEntity->getTier(), 'empty', $journals)
            );
            $bmcEntity->setJournalAmountPerItem(
                $this->blackMarketCraftingService->calculateJournalAmountPerItem(
                    $itemEntity
                        ->getFame(),
                    $bmcEntity->getJournalEntityEmpty()
                        ->getFameToFill()
                )
            );

            $bmcEntity->setAmount(
                $this->blackMarketCraftingService->calculateTotalAmount(
                    $itemEntity->getTier(),
                    $itemEntity->getTotalResourceAmount(),
                    $bmSells
                )
            );
            $totalAmount = $bmcEntity->getAmount();
            $bmcEntity->setPrimResourceTotalAmount(
                $this->blackMarketCraftingService->calculateResourceAmount(
                    $totalAmount,
                    $itemEntity->getPrimaryResourceAmount()
                )
            );
            $bmcEntity->setSecResourceTotalAmount(
                $this->blackMarketCraftingService->calculateResourceAmount(
                    $totalAmount,
                    $itemEntity->getSecondaryResourceAmount()
                )
            );
            $bmcEntity->setJournalTotalAmount(
                $this->blackMarketCraftingService->calculateJournalAmount(
                    $totalAmount,
                    $bmcEntity->getJournalAmountPerItem()
                )
            );
            $bmcEntity->setFameAmount(
                $this->blackMarketCraftingService->calculateFameAmount($totalAmount, $itemEntity->getFame())
            );

            $bmcEntity->setProfitJournals(
                $this->blackMarketCraftingService->calculateProfitJournals(
                    $bmcEntity->getJournalEntityEmpty()
                        ->getBuyOrderPrice(),
                    $bmcEntity->getJournalEntityFull()
                        ->getSellOrderPrice(),
                    $bmcEntity->getJournalTotalAmount()
                )
            );

            //Focus
            $primResource = $bmcEntity->getPrimResource();
            $secResource = $bmcEntity->getSecResource();
            $primResourceCostSell = $primResource->getSellOrderPrice() * $itemEntity->getPrimaryResourceAmount();
            $secResourceCostSell = $secResource->getSellOrderPrice() * $itemEntity->getSecondaryResourceAmount();
            $bmcEntity->setMaterialCostSell(
                $this->blackMarketCraftingService->calculateMaterialCost(
                    $primResourceCostSell + $secResourceCostSell,
                    $bmcEntity->getJournalEntityEmpty()
                        ->getBuyOrderPrice(),
                    $bmcEntity->getJournalAmountPerItem(),
                    47.9
                )
            );
            $bmcEntity->setProfitSell(
                $this->blackMarketCraftingService->calculateProfit(
                    $itemEntity->getSellOrderPrice(),
                    $bmcEntity->getMaterialCostSell()
                )
            );
            $bmcEntity->setProfitPercentageSell(
                $this->blackMarketCraftingService->calculateProfitPercentage(
                    $itemEntity->getSellOrderPrice(),
                    $bmcEntity->getMaterialCostSell()
                )
            );
            $bmcEntity->setProfitGradeSell(
                $this->blackMarketCraftingService->calculateProfitGrade($bmcEntity->getProfitPercentageSell())
            );

            //No Focus
            $primResourceCostBuy = $primResource->getBuyOrderPrice() * $itemEntity->getPrimaryResourceAmount();
            $secResourceCostBuy = $secResource->getBuyOrderPrice() * $itemEntity->getSecondaryResourceAmount();
            $bmcEntity->setMaterialCostBuy(
                $this->blackMarketCraftingService->calculateMaterialCost(
                    $primResourceCostBuy + $secResourceCostBuy,
                    $bmcEntity->getJournalEntityEmpty()
                        ->getBuyOrderPrice(),
                    $bmcEntity->getJournalAmountPerItem(),
                    24.8
                )
            );
            $bmcEntity->setProfitBuy(
                $this->blackMarketCraftingService->calculateProfit(
                    $itemEntity->getSellOrderPrice(),
                    $bmcEntity->getMaterialCostBuy()
                )
            );
            $bmcEntity->setProfitPercentageBuy(
                $this->blackMarketCraftingService->calculateProfitPercentage(
                    $itemEntity->getSellOrderPrice(),
                    $bmcEntity->getMaterialCostBuy()
                )
            );
            $bmcEntity->setProfitGradeBuy(
                $this->blackMarketCraftingService->calculateProfitGrade($bmcEntity->getProfitPercentageBuy())
            );

            $bmcEntity->setComplete(
                $this->blackMarketCraftingService->isComplete([
                    $itemEntity->getSellOrderPrice(),
                    $primResource->getSellOrderPrice(),
                    $primResource->getBuyOrderPrice(),
                    $secResource->getSellOrderPrice(),
                    $secResource->getBuyOrderPrice(),
                    $bmcEntity->getJournalEntityFull()
                        ->getSellOrderPrice(),
                    $bmcEntity->getJournalEntityEmpty()
                        ->getBuyOrderPrice(),
                ])
            );
            $bmcEntity->setCity($city);

            $this->blackMarketCraftingRepository->createOrUpdate($bmcEntity);
        }
        $progressBar->setMessage('Update in ' . $city . ' finished');
        $progressBar->finish();
    }
}
