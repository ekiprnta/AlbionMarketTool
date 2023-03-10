<?php

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\JournalEntity;
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

    public function calculateMaterialCost(
        float|int $resourceCost,
        int $journalPrice,
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
        $bmcEntity->setJournalAmountPerItem(
            $this->calculateJournalAmountPerItem(
                $itemEntity
                    ->getFame(),
                $bmcEntity->getJournalEntityEmpty()
                    ->getFameToFill()
            )
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

        $bmcEntity->setProfitJournals(
            $this->calculateProfitJournals(
                $bmcEntity->getJournalEntityEmpty()
                    ->getBuyOrderPrice(),
                $bmcEntity->getJournalEntityFull()
                    ->getSellOrderPrice(),
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
                $bmcEntity->getJournalEntityFull()
                    ->getSellOrderPrice(),
                $bmcEntity->getJournalEntityEmpty()
                    ->getBuyOrderPrice(),
            ])
        );
        $bmcEntity->setCity($city);

        return $bmcEntity;
    }
}
