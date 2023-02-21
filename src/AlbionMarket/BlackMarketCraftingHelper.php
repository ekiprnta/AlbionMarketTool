<?php

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\factories\ResourceEntityFactory;

class BlackMarketCraftingHelper extends Market
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
        return $fame / $fameToFill;
    }

    public function calculateFameAmount(int $totalAmount, float $fame): float
    {
        return $totalAmount * $fame * self::PREMIUM_FACTOR;
    }

    public function calculateTotalAmount(
        int $tier,
        int $primResourceAmount,
        int $secResourceAmount,
        array $blackMarketSellAmount
    ): int {
        $totalAmount = (string) ($primResourceAmount + $secResourceAmount);
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

    public function calculateProfitA(
        int $totalAmount,
        int $itemPrice,
        float $itemCost,
        float $percentage,
        float $craftingFee,
        float $profitJournals,
    ): float {
        $profit = $this->calculateProfitByPercentage($totalAmount, $itemPrice, $itemCost, $percentage);

        return $profit - $craftingFee + $profitJournals;
    }

    public function calculateProfitJournals(int $emptyJournalPrice, int $fullJournalPrice, float $journalAmount): float
    {
        return ($this->calculateSellOrder($fullJournalPrice) - $emptyJournalPrice) * $journalAmount;
    }

    private function calculateProfitByPercentage(
        int $totalAmount,
        int $itemPrice,
        float $itemCost,
        float $percentage
    ): float {
        $rate = (self::RRR_BASE_PERCENTAGE - $percentage) / 100;
        $itemSellPrice = $this->calculateSellOrder($itemPrice);
        return ($itemSellPrice - ($itemCost * $rate)) * $totalAmount;
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
}
