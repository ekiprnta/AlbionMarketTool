<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;

class BlackMarketCraftingHelper extends Market
{
    public static function calculateResources(BlackMarketCraftingEntity $bmcEntity, array $resources): array
    {
        $primResourceName = $bmcEntity->getItem()->getPrimaryResource();
        $secResourceName = $bmcEntity->getItem()->getSecondaryResource();
        $tier = $bmcEntity->getItem()->getTier();

        $primResource = null;
        $secResource = null;
        /** @var ResourceEntity $resource */
        foreach ($resources as $resource) {
            if ($tier === $resource->getTier()) {
                if ($resource->getRealName() === $primResourceName) {
                    $primResource = $resource;
                }
                if ($resource->getRealName() === $secResourceName) {
                    $secResource = $resource;
                }
            }
        }

        return [
            'primaryResource' => $primResource,
            'secondaryResource' => $secResource,
        ];
    }


    public static function calculateJournals(BlackMarketCraftingEntity $bmcEntity, array $journals): array
    {
        $tier = $bmcEntity->getItem()->getTier();

        $journalAmountPerItem = null;
        $emptyJournal = null;
        $fullJournal = null;
        /** @var JournalEntity $journal */
        foreach ($journals as $journal) {
            if ($tier[0] === $journal->getTier()) {
                if ($journal->getFillStatus() === 'empty') {
                    $journalAmountPerItem = $bmcEntity->getItem()->getFame() / $journal->getFameToFill();
                    $emptyJournal = $journal;
                }
                if ($journal->getFillStatus() === 'full') {
                    $fullJournal = $journal;
                }
            }
        }
        return [
            'full' => $fullJournal,
            'empty' => $emptyJournal,
            'amount' => $journalAmountPerItem,
        ];
    }

    public static function calculateFameAmount(BlackMarketCraftingEntity $bmcEntity): float
    {
        return $bmcEntity->getTotalAmount() * $bmcEntity->getItem()->getFame() * self::PREMIUM_FACTOR;
    }

    public static function calculateTotalAmount(BlackMarketCraftingEntity $bmcEntity, int $weight): array
    {
        $resourceWeightForItem = $bmcEntity->getPrimResource()->getWeight() *
            ($bmcEntity->getItem()->getPrimaryResourceAmount() +
                $bmcEntity->getItem()->getSecondaryResourceAmount());
        $journalWeightForItem = $bmcEntity->getJournalEntityEmpty()->getWeight() *
            $bmcEntity->getJournalAmountPerItem();

        $totalAmount = (int)($weight / ($resourceWeightForItem + $journalWeightForItem));

        return [
            'totalAmount' => ($totalAmount),
            'primResourceAmount' => ($totalAmount * $bmcEntity->getItem()->getPrimaryResourceAmount()),
            'secResourceAmount' => ($totalAmount * $bmcEntity->getItem()->getSecondaryResourceAmount()),
            'journalAmount' => ((int)ceil($totalAmount * $bmcEntity->getJournalAmountPerItem())),
            'totalItemWeight' => ($totalAmount * $bmcEntity->getItem()->getWeight()),
        ];
    }


    public static function calculateCraftingFee(
        BlackMarketCraftingEntity $bmcEntity,
        int $feeProHundredNutrition
    ): float {
        $nutrition = $bmcEntity->getItem()->getItemValue() * self::NUTRITION_FACTOR;
        return $nutrition * $feeProHundredNutrition / 100;
    }

    public static function calculateProfit(
        BlackMarketCraftingEntity $bmcEntity,
        float $percentage,
        string $order,
    ): array {
        if ($order === '1') {
            $itemCost = $bmcEntity->getPrimResource()->getSellOrderPrice() *
                $bmcEntity->getItem()->getPrimaryResourceAmount() +
                $bmcEntity->getSecResource()->getSellOrderPrice() *
                $bmcEntity->getItem()->getSecondaryResourceAmount();

            $primAge = $bmcEntity->getPrimResource()->getSellOrderAge();
            $secAge = $bmcEntity->getSecResource()->getSellOrderAge();
        } else {
            $itemCost = parent::calculateBuyOrder($bmcEntity->getPrimResource()->getBuyOrderPrice() *
                    $bmcEntity->getItem()->getPrimaryResourceAmount() +
                    $bmcEntity->getSecResource()->getBuyOrderPrice() *
                    $bmcEntity->getItem()->getSecondaryResourceAmount());

            $primAge = $bmcEntity->getPrimResource()->getBuyOrderAge();
            $secAge = $bmcEntity->getSecResource()->getBuyOrderAge();
        }

        $profit = self:: calculateProfitByPercentage($bmcEntity, $itemCost, $percentage);
        $craftingFee = $bmcEntity->getCraftingFee();
        $profitJournals = $bmcEntity->getProfitBooks();

        return [
            'profit' => $profit - $craftingFee + $profitJournals,
            'primAge' => $primAge,
            'secAge' => $secAge,
        ];
    }

    public static function calculateProfitBooks(BlackMarketCraftingEntity $bmcEntity): float
    {
        return (parent::calculateSellOrder($bmcEntity->getJournalEntityFull()->getSellOrderPrice()) -
                $bmcEntity->getJournalEntityEmpty()->getBuyOrderPrice()) *
            $bmcEntity->getJournalAmount();
    }

    public static function calculateProfitByPercentage(
        BlackMarketCraftingEntity $bmcEntity,
        float $itemCost,
        float $percentage
    ): float {
        $rate = (self::RRR_BASE_PERCENTAGE - $percentage) / 100;
        $amount = $bmcEntity->getTotalAmount();
        $itemSellPrice = parent::calculateSellOrder($bmcEntity->getItem()->getSellOrderPrice());
        return ($itemSellPrice - ($itemCost * $rate)) * $amount;
    }

    public static function calculateWeightProfitQuotient(float|int $profit, int $weight): float
    {
        return parent::calculateWeightProfitQuotient($profit, $weight);
    }

    public static function calculateProfitGrade(float $quotient): string
    {
        return parent::calculateProfitGrade($quotient);
    }

    public static function calculateItemValue(BlackMarketCraftingEntity $bmcEntity): int
    {
           return $bmcEntity->getItem()->getSellOrderPrice()* $bmcEntity->getTotalAmount();
    }
}