<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\AdvancedEntities\RefiningEntity;
use MZierdt\Albion\repositories\AdvancedRepository\BlackMarketCraftingRepository;
use MZierdt\Albion\repositories\AdvancedRepository\RefiningRepository;

class CraftingService extends Market
{
    public function __construct(
        private readonly BlackMarketCraftingRepository $blackMarketCraftingRepository,
        private readonly BlackMarketCraftingService $blackMarketCraftingService,
        private readonly RefiningRepository $refiningRepository,
        private readonly RefiningService $refiningService,
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

    public function getAllRefiningByCity(string $itemCity, float $percentage): array
    {
        if (empty($percentage)) {
            $percentage = 53.9;
        }

        $refiningEntities = $this->refiningRepository->getAllRefiningByCity($itemCity);

        /** @var RefiningEntity $refiningEntity */
        foreach ($refiningEntities as $refiningEntity) {
            $refinedResource = $refiningEntity->getRefinedResource();

            $rawResource = $refiningEntity->getRawResource();
            $lowerResource = $refiningEntity->getLowerResource();
            $refiningEntity->setMaterialCostSell(
                $this->refiningService->calculateResourceCost(
                    $rawResource->getSellOrderPrice(),
                    $lowerResource->getSellOrderPrice(),
                    $refiningEntity->getAmountRawResource(),
                    $percentage
                )
            );
            $refiningEntity->setProfitSell(
                $this->refiningService->calculateProfit(
                    $refinedResource->getSellOrderPrice(),
                    $refiningEntity->getMaterialCostSell()
                )
            );
            $refiningEntity->setProfitPercentageSell(
                $this->refiningService->calculateProfitPercentage(
                    $refinedResource->getSellOrderPrice(),
                    $refiningEntity->getMaterialCostSell()
                )
            );
            $refiningEntity->setProfitGradeSell(
                $this->calculateProfitGrade($refiningEntity->getProfitPercentageSell())
            );

            $refiningEntity->setMaterialCostBuy(
                $this->refiningService->calculateResourceCost(
                    $rawResource->getBuyOrderPrice(),
                    $lowerResource->getBuyOrderPrice(),
                    $refiningEntity->getAmountRawResource(),
                    $percentage
                )
            );
            $refiningEntity->setProfitBuy(
                $this->calculateProfit($refinedResource->getSellOrderPrice(), $refiningEntity->getMaterialCostBuy())
            );
            $refiningEntity->setProfitPercentageBuy(
                $this->calculateProfitPercentage(
                    $refinedResource->getSellOrderPrice(),
                    $refiningEntity->getMaterialCostBuy()
                )
            );
            $refiningEntity->setProfitGradeBuy($this->calculateProfitGrade($refiningEntity->getProfitPercentageBuy()));
        }

        return $refiningEntities;
    }

}