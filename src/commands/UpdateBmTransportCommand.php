<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionMarket\BlackMarketTransportingService;
use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketTransportEntity;
use MZierdt\Albion\repositories\AdvancedRepository\BlackMarketTransportingRepository;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'market:bmTransport', description: 'Update Calculations for blackmarket/transporting')]
class UpdateBmTransportCommand extends Command
{
    public function __construct(
        private readonly BlackMarketTransportingService $bmtService,
        private readonly BlackMarketTransportingRepository $bmtRepository,
        private readonly ItemRepository $itemRepository,
        private readonly ConfigService $configService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bmItems = $this->itemRepository->getItemsByLocationForBM('BLack Market');
        try {
            $amountConfig = $this->configService->getBlackMarketSells();
        } catch (\JsonException $jsonException) {
            $output->writeln($jsonException->getMessage());
            return self::FAILURE;
        }

        $city = 'Fort Sterling';
        $output->writeln('Updating Transport from ' . $city . '...');
        $this->updateCalculations($city, $output, $bmItems, $amountConfig);

        $city = 'Lymhurst';
        $output->writeln(PHP_EOL . 'Updating Transport from ' . $city . '...');
        $this->updateCalculations($city, $output, $bmItems, $amountConfig);

        $city = 'Bridgewatch';
        $output->writeln(PHP_EOL . 'Updating Transport from ' . $city . '...');
        $this->updateCalculations($city, $output, $bmItems, $amountConfig);

        $city = 'Martlock';
        $output->writeln(PHP_EOL . 'Updating Transport from ' . $city . '...');
        $this->updateCalculations($city, $output, $bmItems, $amountConfig);

        $city = 'Thetford';
        $output->writeln(PHP_EOL . 'Updating Transport from ' . $city . '...');
        $this->updateCalculations($city, $output, $bmItems, $amountConfig);

        $output->writeln(PHP_EOL . 'Done');
        return self::SUCCESS;
    }

    private function updateCalculations(
        string $city,
        OutputInterface $output,
        array $bmItems,
        array $amountConfig
    ): void {
        $cityItems = $this->itemRepository->getItemsByLocationForBM($city);
        $progressBar = ProgressBarService::getProgressBar($output, is_countable($cityItems) ? count($cityItems) : 0);
        $bmtEntities = [];
        foreach ($bmItems as $bmItem) {
            $bmtEntities[] = new BlackMarketTransportEntity($bmItem);
        }
        /** @var BlackMarketTransportEntity $bmtEntity */
        foreach ($bmtEntities as $bmtEntity) {
            $bmItem = $bmtEntity->getBmItem();

            $message = sprintf('Update bmtEntity: %s from %s', $bmItem->getRealName(), $city);
            $progressBar->setMessage($message);
            $progressBar->advance();
            $progressBar->display();

            $bmtEntity->setCityItem($this->bmtService->calculateCityItem($bmItem, $cityItems));
            $cityItem = $bmtEntity->getCityItem();
            $bmtEntity->setAmount(
                $this->bmtService->calculateAmount(
                    $cityItem
                        ->getPrimaryResourceAmount(),
                    $cityItem
                        ->getSecondaryResourceAmount(),
                    $amountConfig[$bmItem->getTier()]
                )
            );
            $bmtEntity->setMaterialCostSell($cityItem->getSellOrderPrice());
            $bmtEntity->setProfitSell(
                $this->bmtService->calculateProfit(
                    $bmItem->getSellOrderPrice(),
                    (int) $bmtEntity->getMaterialCostSell()
                )
            );
            $bmtEntity->setProfitPercentageSell(
                $this->bmtService->calculateProfitPercentage(
                    $bmItem->getSellOrderPrice(),
                    $cityItem->getSellOrderPrice()
                )
            );
            $bmtEntity->setProfitGradeSell(
                $this->bmtService->calculateProfitGrade($bmtEntity->getProfitPercentageSell())
            );

            $cityItemPrice = $cityItem->getBuyOrderPrice();
            $bmtEntity->setMaterialCostBuy($this->bmtService->calculateBuyOrder($cityItemPrice));
            $bmtEntity->setProfitBuy(
                $this->bmtService->calculateProfit($bmItem->getBuyOrderPrice(), (int) $bmtEntity->getMaterialCostBuy())
            );
            $bmtEntity->setProfitPercentageBuy(
                $this->bmtService->calculateProfitPercentage($bmItem->getBuyOrderPrice(), $cityItemPrice)
            );
            $bmtEntity->setProfitGradeBuy(
                $this->bmtService->calculateProfitGrade($bmtEntity->getProfitPercentageBuy())
            );

            $bmtEntity->setComplete(
                $this->bmtService->isComplete(
                    [$bmItem->getSellOrderPrice(), $cityItem->getSellOrderPrice(), $cityItem->getBuyOrderPrice()]
                )
            );

            $this->bmtRepository->createOrUpdate($bmtEntity);
        }
    }
}