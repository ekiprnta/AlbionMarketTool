<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionMarket\TransmutationService;
use MZierdt\Albion\Entity\AdvancedEntities\TransmutationEntity;
use MZierdt\Albion\repositories\AdvancedRepository\TransmutationRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\GlobalDiscountService;
use MZierdt\Albion\Service\ProgressBarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'market:transmutation', description: 'Update Calculations for resource/transmutation')]
class UpdateTransmutationCommand extends Command
{
    public function __construct(
        private readonly TransmutationService $transmutationService,
        private readonly TransmutationRepository $transmutationRepository,
        private readonly ResourceRepository $resourceRepository,
        private readonly ConfigService $configService,
        private readonly GlobalDiscountService $discountService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $transmutationWays = $this->configService->getTransmutationWays();
        $transmutationCost = $this->configService->getTransmutationCost();
        $globalDiscount = $this->discountService->getGlobalDiscount();

        $transmutationEntityList = [];
        foreach ($transmutationWays as $pathName => $transmutationWay) {
            $transmutationEntityList[] = new TransmutationEntity($pathName, $transmutationWay, 'fiber');
        }
        foreach ($transmutationWays as $pathName => $transmutationWay) {
            $transmutationEntityList[] = new TransmutationEntity($pathName, $transmutationWay, 'wood');
        }
        foreach ($transmutationWays as $pathName => $transmutationWay) {
            $transmutationEntityList[] = new TransmutationEntity($pathName, $transmutationWay, 'hide');
        }
        foreach ($transmutationWays as $pathName => $transmutationWay) {
            $transmutationEntityList[] = new TransmutationEntity($pathName, $transmutationWay, 'ore');
        }

        $city = 'Fort Sterling';
        $output->writeln('Updating Transmutation from ' . $city . '...');
        $this->updateCalculations($transmutationEntityList, $city, $transmutationCost, $globalDiscount, $output);

        $city = 'Lymhurst';
        $output->writeln(PHP_EOL . 'Updating Transmutation from ' . $city . '...');
        $this->updateCalculations($transmutationEntityList, $city, $transmutationCost, $globalDiscount, $output);

        $city = 'Bridgewatch';
        $output->writeln(PHP_EOL . 'Updating Transmutation from ' . $city . '...');
        $this->updateCalculations($transmutationEntityList, $city, $transmutationCost, $globalDiscount, $output);

        $city = 'Martlock';
        $output->writeln(PHP_EOL . 'Updating Transmutation from ' . $city . '...');
        $this->updateCalculations($transmutationEntityList, $city, $transmutationCost, $globalDiscount, $output);

        $city = 'Thetford';
        $output->writeln(PHP_EOL . 'Updating Transmutation from ' . $city . '...');
        $this->updateCalculations($transmutationEntityList, $city, $transmutationCost, $globalDiscount, $output);

        $output->writeln('Done');
        return self::SUCCESS;
    }

    private function updateCalculations(
        array $transmutationEntityList,
        string $city,
        array $transmutationCost,
        float $globalDiscount,
        OutputInterface $output,
    ): void {
        $progressBar = ProgressBarService::getProgressBar($output, (count($transmutationEntityList)));

        $resources = $this->resourceRepository->getRawResourcesByCity($city);
        /** @var TransmutationEntity $transEntity */
        foreach ($transmutationEntityList as $transEntity) {
            $message = sprintf('Update transmutationEntity: %s from %s', $transEntity->getPathName(), $city);
            $progressBar->setMessage($message);
            $progressBar->advance();
            $progressBar->display();

            [$startTier, $endTier] = $this->transmutationService->calculateStartAndEnd($transEntity->getPathName());
            $transEntity->setStartResource(
                $this->transmutationService->calculateResource(
                    $resources,
                    (int) $startTier,
                    $transEntity->getResourceType()
                )
            );
            $transEntity->setEndResource(
                $this->transmutationService->calculateResource(
                    $resources,
                    (int) $endTier,
                    $transEntity->getResourceType()
                )
            );
            $transEntity->setTransmutationPrice(
                $this->transmutationService->calculateTransmutationPrice(
                    $transEntity->getTransmutationPath(),
                    $startTier,
                    $transmutationCost,
                    $globalDiscount
                )
            );

            $transEntity->setMaterialCostSell(
                $transEntity->getStartResource()
                    ->getSellOrderPrice() + $transEntity->getTransmutationPrice()
            );
            $transEntity->setProfitSell(
                $this->transmutationService->calculateProfit(
                    $transEntity->getEndResource()
                        ->getBuyOrderPrice(),
                    $transEntity->getMaterialCostSell()
                )
            );
            $transEntity->setProfitPercentageSell(
                $this->transmutationService->calculateProfitPercentage(
                    $transEntity->getEndResource()
                        ->getSellOrderPrice(),
                    $transEntity->getMaterialCostSell()
                )
            );
            $transEntity->setProfitGradeSell(
                $this->transmutationService->calculateProfitGrade($transEntity->getProfitPercentageSell())
            );

            $transEntity->setMaterialCostBuy(
                $transEntity->getStartResource()
                    ->getBuyOrderPrice() + $transEntity->getTransmutationPrice()
            );
            $transEntity->setProfitBuy(
                $this->transmutationService->calculateProfit(
                    $transEntity->getEndResource()
                        ->getSellOrderPrice(),
                    $transEntity->getMaterialCostBuy()
                )
            );
            $transEntity->setProfitPercentageBuy(
                $this->transmutationService->calculateProfitPercentage(
                    $transEntity->getEndResource()
                        ->getSellOrderPrice(),
                    $transEntity->getMaterialCostBuy()
                )
            );
            $transEntity->setProfitGradeBuy(
                $this->transmutationService->calculateProfitGrade($transEntity->getProfitPercentageBuy())
            );

            $transEntity->setTierColor((int) ($transEntity->getStartResource()->getTier() / 10));
            $transEntity->setEndTierColor((int) ($transEntity->getEndResource()->getTier() / 10));

            $transEntity->setComplete(
                $this->transmutationService->isComplete([
                    $transEntity->getEndResource()
                        ->getSellOrderPrice(),
                    $transEntity->getStartResource()
                        ->getSellOrderPrice(),
                    $transEntity->getStartResource()
                        ->getBuyOrderPrice(),
                    $transEntity->getTransmutationPrice(),
                ])
            );
            $transEntity->setCity($city);

            $this->transmutationRepository->createOrUpdate($transEntity);
        }
        $progressBar->setMessage('Update in ' . $city . ' finished');
        $progressBar->finish();
    }
}
