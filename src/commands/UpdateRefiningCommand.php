<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionMarket\RefiningService;
use MZierdt\Albion\Entity\AdvancedEntities\RefiningEntity;
use MZierdt\Albion\repositories\AdvancedRepository\RefiningRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ProgressBarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'market:refining', description: 'Update Calculations for resource/refining')]
class UpdateRefiningCommand extends Command
{
    public function __construct(
        private readonly RefiningService $refiningService,
        private readonly RefiningRepository $refiningRepository,
        private readonly ResourceRepository $resourceRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $city = 'Fort Sterling';
        $output->writeln('Updating Refining from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Lymhurst';
        $output->writeln(PHP_EOL . 'Updating Refining from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Bridgewatch';
        $output->writeln(PHP_EOL . 'Updating Refining from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Martlock';
        $output->writeln(PHP_EOL . 'Updating Refining from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Thetford';
        $output->writeln(PHP_EOL . 'Updating Refining from ' . $city . '...');
        $this->updateCalculations($city, $output);

        return self::SUCCESS;
    }

    private function updateCalculations(string $city, OutputInterface $output): void
    {
        $resources = $this->resourceRepository->getResourcesByBonusCity($city);
        $rawResources = $this->resourceRepository->getRawResourcesByBonusCity($city);
        $progressBar = ProgressBarService::getProgressBar(
            $output,
            is_countable($resources) ? count($resources) - 1 : 0
        );

        $refiningArray = [];
        foreach ($resources as $resource) {
            if ($resource->getTier() !== 20) {
                $refiningArray[] = new RefiningEntity($resource);
            }
        }
        /** @var RefiningEntity $refiningEntity */
        foreach ($refiningArray as $refiningEntity) {
            $message = sprintf(
                'Update refiningEntity: %s from %s',
                $refiningEntity->getRefinedResource()->getRealName(),
                $city
            );
            $progressBar->setMessage($message);
            $progressBar->advance();
            $progressBar->display();

            $refiningEntity->setAmountRawResource(
                $this->refiningService->calculateAmountRawResource($refiningEntity->getRefinedResource()->getTier())
            );
            $refiningEntity->setRawResource(
                $this->refiningService->calculateResource(
                    $refiningEntity->getRefinedResource()->getTier(),
                    $rawResources
                )
            );
            $lowerTier = $this->refiningService->calculateLowerResourceTier(
                $refiningEntity->getRefinedResource()->getTier()
            );
            $refiningEntity->setLowerResource($this->refiningService->calculateResource($lowerTier, $resources));

            // Sell is the calculation with Focus
            $refiningEntity->setMaterialCostSell(
                $this->refiningService->calculateResourceCost(
                    $refiningEntity->getRawResource()->getBuyOrderPrice(),
                    $refiningEntity->getLowerResource()->getBuyOrderPrice(),
                    $refiningEntity->getAmountRawResource(),
                    53.9
                )
            );
            $refiningEntity->setProfitSell(
                $this->refiningService->calculateProfit(
                    $refiningEntity->getRefinedResource()->getSellOrderPrice(),
                    $refiningEntity->getMaterialCostSell()
                )
            );
            $refiningEntity->setProfitPercentageSell(
                $this->refiningService->calculateProfitPercentage(
                    $refiningEntity->getRefinedResource()->getSellOrderPrice(),
                    $refiningEntity->getMaterialCostSell()
                )
            );
            $refiningEntity->setProfitGradeSell(
                $this->refiningService->calculateProfitGrade($refiningEntity->getProfitPercentageSell())
            );

            //Buy is the calculation without Focus
            $refiningEntity->setMaterialCostBuy(
                $this->refiningService->calculateResourceCost(
                    $refiningEntity->getRawResource()->getBuyOrderPrice(),
                    $refiningEntity->getLowerResource()->getBuyOrderPrice(),
                    $refiningEntity->getAmountRawResource(),
                    36.7
                )
            );
            $refiningEntity->setProfitBuy(
                $this->refiningService->calculateProfit(
                    $refiningEntity->getRefinedResource()->getSellOrderPrice(),
                    $refiningEntity->getMaterialCostBuy()
                )
            );
            $refiningEntity->setProfitPercentageBuy(
                $this->refiningService->calculateProfitPercentage(
                    $refiningEntity->getRefinedResource()->getSellOrderPrice(),
                    $refiningEntity->getMaterialCostBuy()
                )
            );
            $refiningEntity->setProfitGradeBuy(
                $this->refiningService->calculateProfitGrade($refiningEntity->getProfitPercentageSell())
            );

            $refiningEntity->setComplete(
                $this->refiningService->isComplete(
                    [
                        $refiningEntity->getRefinedResource()->getSellOrderPrice(),
                        $refiningEntity->getLowerResource()->getBuyOrderPrice(),
                        $refiningEntity->getRawResource()->getBuyOrderPrice()
                    ]
                )
            );
            $refiningEntity->setCity($city);

            $this->refiningRepository->createOrUpdate($refiningEntity);
        }
    }
}