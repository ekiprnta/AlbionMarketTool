<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\AdvancedEntities\MarketEntity;
use MZierdt\Albion\repositories\AdvancedRepository\BlackMarketCraftingRepository;
use MZierdt\Albion\repositories\AdvancedRepository\RefiningRepository;

class CraftingService extends MarketEntity
{
    public function __construct(
        private readonly BlackMarketCraftingRepository $blackMarketCraftingRepository,
        private readonly BlackMarketCraftingService $blackMarketCraftingService,
        private readonly RefiningRepository $refiningRepository,
    ) {
    }

    public function getAllBmCraftingByCity(string $itemCity, float $percentage): array
    {
        if (empty($percentage)) {
            $percentage = 47.9;
        }

        $bmCraftingEntities = $this->blackMarketCraftingRepository->getAllBmCraftingByCity($itemCity);

        /** @var BlackMarketCraftingEntity $bmcEntity */
        foreach ($bmCraftingEntities as $bmcEntity) {
            $itemEntity = $bmcEntity->getItem();

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
                    $percentage
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

            $primResourceCostBuy = $primResource->getBuyOrderPrice() * $itemEntity->getPrimaryResourceAmount();
            $secResourceCostBuy = $secResource->getBuyOrderPrice() * $itemEntity->getSecondaryResourceAmount();
            $bmcEntity->setMaterialCostBuy(
                $this->blackMarketCraftingService->calculateMaterialCost(
                    $primResourceCostBuy + $secResourceCostBuy,
                    $bmcEntity->getJournalEntityEmpty()
                        ->getBuyOrderPrice(),
                    $bmcEntity->getJournalAmountPerItem(),
                    $percentage
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
        }

        return $bmCraftingEntities;
    }

}