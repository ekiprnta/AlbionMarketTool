<?php

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\factories\ResourceEntityFactory;

class BlackMarketCraftingService extends Market
{
    public function calculateResource(string $resourceName, int $tier, array $resources): ResourceEntity
    {
        /** @var ResourceEntity $resource */
        foreach ($resources as $resource) {
            if (($tier === $resource->getTier()) && $resource->getRealName() === $resourceName) {
                return $resource;
            }
        }
        return ResourceEntityFactory::getEmptyResourceEntity();
    }

    public function calculateJournal(int $tier, string $fillStatus, array $journals): ?JournalEntity
    {
        $baseTier = (int) ($tier / 10);
        /** @var JournalEntity $journal */
        foreach ($journals as $journal) {
            if (($baseTier * 10 === $journal->getTier()) && $journal->getFillStatus() === $fillStatus) {
                return $journal;
            }
        }
        return null;
    }

    public function calculateJournalAmountPerItem(float $fame, int $fameToFill): float
    {
        return round($fame / $fameToFill, 2);
    }

    public function calculateFameAmount(int $totalAmount, float $fame): float
    {
        return $totalAmount * $fame * self::PREMIUM_FACTOR;
    }

    public function calculateTotalAmount(int $tier, int $totalAmount, array $blackMarketSellAmount): int
    {
        return $blackMarketSellAmount[$tier][$totalAmount];
    }

    public function calculateResourceAmount(int $totalAmount, int $resourceAmount): int
    {
        return $totalAmount * $resourceAmount;
    }

    public function calculateJournalAmount(int $totalAmount, float $journalAmountPerItem): float
    {
        return $totalAmount * $journalAmountPerItem;
    }

    public function calculateTotalItemWeight(int $totalAmount, float $weight): float
    {
        return $totalAmount * $weight;
    }

    public function calculateCraftingFee(int $itemValue, int $feeProHundredNutrition): float
    {
        $nutrition = $itemValue * self::NUTRITION_FACTOR;
        return $nutrition * $feeProHundredNutrition / 100;
    }

    public function calculateProfitJournals(
        int $fullJournalPrice,
        float $emptyJournalPrice,
        float $journalAmount
    ): float {
        return ($this->calculateSellOrder($fullJournalPrice) - $emptyJournalPrice) * $journalAmount;
    }

    public function calculateItemValue(int $totalAmount, int $price): int
    {
        return $totalAmount * $price;
    }

    public function calculateBuyOrderItemCost(
        int $primResourcePrice,
        int $primResourceAmount,
        int $secResourcePrice,
        int $secResourceAmount
    ): float {
        return $this->calculateBuyOrder(
            $primResourcePrice * $primResourceAmount +
            $secResourcePrice * $secResourceAmount
        );
    }

    public function calculateSellOrderItemCost(
        int $primResourcePrice,
        int $primResourceAmount,
        int $secResourcePrice,
        int $secResourceAmount
    ): float {
        return $primResourcePrice * $primResourceAmount +
            $secResourcePrice * $secResourceAmount;
    }

    public function calculateMaterialCost(
        float|int $resourceCost,
        float $journalPrice,
        float $journalAmountPerItem,
        float $percentage
    ): float {
        $rate = (self::RRR_BASE_PERCENTAGE - $percentage) / 100;
        return $resourceCost * $rate + $journalPrice * $journalAmountPerItem;
    }

    public function getCraftingRates(): array
    {
        return [
            'No City Bonus No Focus' => 15.2,
            'No City Bonus Focus' => 43.5,
            'City Bonus No Focus' => 24.8,
            'City Bonus Focus' => 47.9,
        ];
    }

    public function calculateBmcEntity(
        BlackMarketCraftingEntity $bmcEntity,
        array $resources,
        array $journals,
        array $bmSells,
        string $city
    ): BlackMarketCraftingEntity {
        $itemEntity = $bmcEntity->getItem();
        $bmcEntity->setPrimResource(
            $this->calculateResource($itemEntity->getPrimaryResource(), $itemEntity->getTier(), $resources)
        );
        $bmcEntity->setSecResource(
            $this->calculateResource($itemEntity->getSecondaryResource(), $itemEntity->getTier(), $resources)
        );
        $bmcEntity->setJournalEntityFull($this->calculateJournal($itemEntity->getTier(), 'full', $journals));
        $bmcEntity->setJournalEntityEmpty($this->calculateJournal($itemEntity->getTier(), 'empty', $journals));

        $journalEntityEmpty = $bmcEntity->getJournalEntityEmpty();
        $bmcEntity->setJournalAmountPerItem(
            $this->calculateJournalAmountPerItem($itemEntity->getFame(), $journalEntityEmpty->getFameToFill())
        );

        $bmcEntity->setAmount(
            $this->calculateTotalAmount($itemEntity->getTier(), $itemEntity->getTotalResourceAmount(), $bmSells)
        );
        $totalAmount = $bmcEntity->getAmount();
        $bmcEntity->setPrimResourceTotalAmount(
            $this->calculateResourceAmount($totalAmount, $itemEntity->getPrimaryResourceAmount())
        );
        $bmcEntity->setSecResourceTotalAmount(
            $this->calculateResourceAmount($totalAmount, $itemEntity->getSecondaryResourceAmount())
        );
        $bmcEntity->setJournalTotalAmount(
            $this->calculateJournalAmount($totalAmount, $bmcEntity->getJournalAmountPerItem())
        );
        $bmcEntity->setFameAmount($this->calculateFameAmount($totalAmount, $itemEntity->getFame()));

        $journalEntityFull = $bmcEntity->getJournalEntityFull();
        $bmcEntity->setProfitJournals(
            $this->calculateProfitJournals(
                $journalEntityFull->getSellOrderPrice(),
                $this->calculateBuyOrder($journalEntityEmpty->getBuyOrderPrice()),
                $bmcEntity->getJournalTotalAmount()
            )
        );

        $primResource = $bmcEntity->getPrimResource();
        $secResource = $bmcEntity->getSecResource();
        $bmcEntity->setComplete(
            $this->isComplete([
                $itemEntity->getSellOrderPrice(),
                $primResource->getSellOrderPrice(),
                $primResource->getBuyOrderPrice(),
                $secResource->getSellOrderPrice(),
                $secResource->getBuyOrderPrice(),
                $journalEntityFull->getSellOrderPrice(),
                $journalEntityEmpty->getBuyOrderPrice(),
            ])
        );
        $bmcEntity->setCity($city);

        return $bmcEntity;
    }

    public function calculateProfitByPercentage(
        BlackMarketCraftingEntity $bmcEntity,
        float $percentage,
        MaterialEntity $tome,
    ): BlackMarketCraftingEntity {
        $itemEntity = $bmcEntity->getItem();

        $primResource = $bmcEntity->getPrimResource();
        $secResource = $bmcEntity->getSecResource();
        $primResourceCostSell = $primResource->getSellOrderPrice() * $itemEntity->getPrimaryResourceAmount();
        $secResourceCostSell = $secResource->getSellOrderPrice() * $itemEntity->getSecondaryResourceAmount();

        $journalPriceEmpty = $this->calculateBuyOrder($bmcEntity->getJournalEntityEmpty()->getBuyOrderPrice());
        $tomePrice = $this->calculateBuyOrder($tome->getBuyOrderPrice());

        if ($bmcEntity->getItem()->getName() === 'bag_insight') {
            $bmcEntity->setMaterialCostSell(
                $tomePrice +
                $this->calculateMaterialCost(
                    $primResourceCostSell + $secResourceCostSell,
                    $journalPriceEmpty,
                    $bmcEntity->getJournalAmountPerItem(),
                    $percentage
                )
            );
        } else {
            $bmcEntity->setMaterialCostSell(
                $this->calculateMaterialCost(
                    $primResourceCostSell + $secResourceCostSell,
                    $journalPriceEmpty,
                    $bmcEntity->getJournalAmountPerItem(),
                    $percentage
                )
            );
        }

        $bmcEntity->setProfitSell(
            $this->calculateProfit($itemEntity->getSellOrderPrice(), $bmcEntity->getMaterialCostSell())
        );
        $bmcEntity->setProfitPercentageSell(
            $this->calculateProfitPercentage($itemEntity->getSellOrderPrice(), $bmcEntity->getMaterialCostSell())
        );
        $bmcEntity->setProfitGradeSell($this->calculateProfitGrade($bmcEntity->getProfitPercentageSell()));

        $primResourceCostBuy = $this->calculateBuyOrder(
            $primResource->getBuyOrderPrice()
        ) * $itemEntity->getPrimaryResourceAmount();
        $secResourceCostBuy = $this->calculateBuyOrder(
            $secResource->getBuyOrderPrice()
        ) * $itemEntity->getSecondaryResourceAmount();

        if ($bmcEntity->getItem()->getName() === 'bag_insight') {
            $bmcEntity->setMaterialCostBuy(
                $this->calculateMaterialCost(
                    $tomePrice +
                    $primResourceCostBuy + $secResourceCostBuy,
                    $journalPriceEmpty,
                    $bmcEntity->getJournalAmountPerItem(),
                    $percentage
                )
            );
        } else {
            $bmcEntity->setMaterialCostBuy(
                $this->calculateMaterialCost(
                    $primResourceCostBuy + $secResourceCostBuy,
                    $journalPriceEmpty,
                    $bmcEntity->getJournalAmountPerItem(),
                    $percentage
                )
            );
        }

        $bmcEntity->setProfitBuy(
            $this->calculateProfit($itemEntity->getSellOrderPrice(), $bmcEntity->getMaterialCostBuy())
        );
        $bmcEntity->setProfitPercentageBuy(
            $this->calculateProfitPercentage($itemEntity->getSellOrderPrice(), $bmcEntity->getMaterialCostBuy())
        );
        $bmcEntity->setProfitGradeBuy($this->calculateProfitGrade($bmcEntity->getProfitPercentageBuy()));
        return $bmcEntity;
    }
}
