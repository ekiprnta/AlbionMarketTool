<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionMarket\NoSpecCraftingService;
use MZierdt\Albion\Entity\AdvancedEntities\NoSpecEntity;
use MZierdt\Albion\repositories\AdvancedRepository\NoSpecRepository;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\MaterialRepository;
use MZierdt\Albion\Service\ProgressBarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'market:noSpec', description: 'Update Calculations for noSpec/crafting')]
class UpdateNoSpecCraftingCommand extends Command
{
    public function __construct(
        private readonly NoSpecCraftingService $noSpecCraftingService,
        private readonly NoSpecRepository $noSpecRepository,
        private readonly ItemRepository $itemRepository,
        private readonly MaterialRepository $materialRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $city = 'Fort Sterling';
        $output->writeln('Updating NoSpecCrafting from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Lymhurst';
        $output->writeln(PHP_EOL . 'Updating NoSpecCrafting from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Bridgewatch';
        $output->writeln(PHP_EOL . 'Updating NoSpecCrafting from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Martlock';
        $output->writeln(PHP_EOL . 'Updating NoSpecCrafting from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Thetford';
        $output->writeln(PHP_EOL . 'Updating NoSpecCrafting from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $output->writeln(PHP_EOL . 'Done');
        return self::SUCCESS;
    }

    private function updateCalculations(string $city, OutputInterface $output): void
    {
        $capes = $this->itemRepository->getArtifactCapesByCity($city);
        $royalItems = $this->itemRepository->getRoyalItemsByCity($city);
        $capesAndRoyalItems = array_merge($capes, $royalItems);

        $defaultCapes = $this->itemRepository->getDefaultCapesByCity($city);
        $defaultArmor = $this->itemRepository->getDefaultArmor($city);
        $defaultItems = array_merge($defaultArmor, $defaultCapes);

        $heartsAndSigils = $this->materialRepository->getHeartsAndSigilsByCity($city);
        $artifacts = $this->materialRepository->getCapeArtifactsByCity($city);

        $noSpecEntities = [];
        foreach ($capesAndRoyalItems as $item) {
            $noSpecEntities[] = new NoSpecEntity($item);
        }

        $progressBar = ProgressBarService::getProgressBar($output, count($noSpecEntities));
        /** @var NoSpecEntity $noSpecEntity */
        foreach ($noSpecEntities as $noSpecEntity) {
            $specialItem = $noSpecEntity->getSpecialItem();
            $message = sprintf('Update NoSpecEntity: %s from %s', $specialItem->getRealName(), $city);
            $progressBar->setMessage($message);
            $progressBar->advance();
            $progressBar->display();

            $noSpecEntity->setDefaultItem(
                $this->noSpecCraftingService->calculateDefaultItem(
                    $specialItem->getTier(),
                    $specialItem->getPrimaryResource(),
                    $defaultItems
                )
            );

            $noSpecEntity->setSecondResource(
                $this->noSpecCraftingService->calculateSecondResource(
                    $specialItem
                        ->getSecondaryResource(),
                    $specialItem
                        ->getTier(),
                    $heartsAndSigils
                )
            );
            $noSpecEntity->setArtifact(
                $this->noSpecCraftingService->calculateArtifact(
                    $specialItem
                        ->getArtifact(),
                    $specialItem
                        ->getTier(),
                    $artifacts
                )
            );
            if ($noSpecEntity->getArtifact() === null) {
                $artifactPrice = 1;
            } else {
                $artifactPrice = $noSpecEntity->getArtifact()
                    ->getBuyOrderPrice();
            }

            $defaultItem = $noSpecEntity->getDefaultItem();
            $secondResource = $noSpecEntity->getSecondResource();
            $noSpecEntity->setMaterialCostSell(
                $this->noSpecCraftingService->calculateMaterialCost(
                    $defaultItem->getSellOrderPrice(),
                    $secondResource->getSellOrderPrice(),
                    $specialItem->getSecondaryResourceAmount(),
                    $artifactPrice
                )
            );
            $noSpecEntity->setProfitSell(
                $this->noSpecCraftingService->calculateProfit(
                    $specialItem->getSellOrderPrice(),
                    $noSpecEntity->getMaterialCostSell()
                )
            );
            $noSpecEntity->setProfitPercentageSell(
                $this->noSpecCraftingService->calculateProfitPercentage(
                    $specialItem->getSellOrderPrice(),
                    $noSpecEntity->getMaterialCostSell()
                )
            );
            $noSpecEntity->setProfitGradeSell(
                $this->noSpecCraftingService->calculateProfitGrade($noSpecEntity->getProfitPercentageSell())
            );

            $noSpecEntity->setMaterialCostBuy(
                $this->noSpecCraftingService->calculateMaterialCost(
                    $defaultItem->getBuyOrderPrice(),
                    $secondResource->getBuyOrderPrice(),
                    $specialItem->getSecondaryResourceAmount(),
                    $artifactPrice
                )
            );
            $noSpecEntity->setProfitBuy(
                $this->noSpecCraftingService->calculateProfit(
                    $specialItem->getSellOrderPrice(),
                    $noSpecEntity->getMaterialCostBuy()
                )
            );
            $noSpecEntity->setProfitPercentageBuy(
                $this->noSpecCraftingService->calculateProfitPercentage(
                    $specialItem->getSellOrderPrice(),
                    $noSpecEntity->getMaterialCostBuy()
                )
            );
            $noSpecEntity->setProfitGradeBuy(
                $this->noSpecCraftingService->calculateProfitGrade($noSpecEntity->getProfitPercentageBuy())
            );

            $noSpecEntity->setComplete(
                $this->noSpecCraftingService->isComplete(
                    [
                        $specialItem->getSellOrderPrice(),
                        $defaultItem->getSellOrderPrice(),
                        $defaultItem->getBuyOrderPrice(),
                        $secondResource->getSellOrderPrice(),
                        $secondResource->getBuyOrderPrice(),
                    ]
                )
            );
            $noSpecEntity->setCity($city);

            $this->noSpecRepository->createOrUpdate($noSpecEntity);
        }
        $progressBar->setMessage('Update in ' . $city . ' finished');
        $progressBar->finish();
    }
}
