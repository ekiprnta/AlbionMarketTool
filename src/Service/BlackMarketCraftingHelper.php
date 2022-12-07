<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;

class BlackMarketCraftingHelper
{
    private const NUTRITION_FACTOR = 0.1125;
    public const MARKET_SETUP = 0.025;
    public const MARKET_FEE = 0.04;

    private const RRR_BASE_PERCENTAGE = 100;

    public static function calculateResources(BlackMarketCraftingEntity $bmcEntity, array $resources)
    {
        $primResourceName = $bmcEntity->getItem()->getPrimaryResource();
        $secResourceName = $bmcEntity->getItem()->getSecondaryResource();
        $tier = $bmcEntity->getItem()->getTier();

        /** @var ResourceEntity $resource */
        $primResource = null;
        $secResource = null;
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
        $fameFactor = $bmcEntity->getItem()->getFameFactor();
        $tier = $bmcEntity->getItem()->getTier();
        $primAmount = $bmcEntity->getItem()->getPrimaryResourceAmount();
        $secAmount = $bmcEntity->getItem()->getSecondaryResourceAmount();

        $totalAmount = $primAmount + $secAmount;

        $journalAmountPerItem = null;
        $emptyJournal = null;
        $fullJournal = null;
        /** @var JournalEntity $journal */
        foreach ($journals as $journal) {
            if ($tier[0] === $journal->getTier()) {
                if ($journal->getFillStatus() === 'empty') {
                    $journalAmountPerItem = ($fameFactor * $totalAmount) / $journal->getFameToFill();
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

            $primAge = TimeHelper::calculateAgeOfPrices($bmcEntity->getPrimResource()->getSellOrderPriceDate());
            $secAge = TimeHelper::calculateAgeOfPrices($bmcEntity->getSecResource()->getSellOrderPriceDate());
        } else {
            $itemCost = ($bmcEntity->getPrimResource()->getBuyOrderPrice() *
                    $bmcEntity->getItem()->getPrimaryResourceAmount() +
                    $bmcEntity->getSecResource()->getBuyOrderPrice() *
                    $bmcEntity->getItem()->getSecondaryResourceAmount()) *
                (1 + self::MARKET_SETUP);

            $primAge = TimeHelper::calculateAgeOfPrices($bmcEntity->getPrimResource()->getBuyOrderPriceDate());
            $secAge = TimeHelper::calculateAgeOfPrices($bmcEntity->getSecResource()->getBuyOrderPriceDate());
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
        return (($bmcEntity->getJournalEntityFull()->getBuyOrderPrice() *
                    (1 - self::MARKET_FEE - self::MARKET_SETUP)) -
                $bmcEntity->getJournalEntityEmpty()->getSellOrderPrice()) *
            $bmcEntity->getJournalAmount();
    }

    public static function calculateProfitByPercentage(
        BlackMarketCraftingEntity $bmcEntity,
        float $itemCost,
        float $percentage
    ): float {
        $rate = (self::RRR_BASE_PERCENTAGE - $percentage) / 100;
        $amount = $bmcEntity->getTotalAmount();
        $itemSellPrice = $bmcEntity->getItem()->getSellOrderPrice() * (1 - self::MARKET_SETUP - self::MARKET_FEE);
        return ($itemSellPrice - ($itemCost * $rate)) * $amount;
    }

    public static function calculateWeightProfitQuotient(BlackMarketCraftingEntity $bmcEntity): float
    {
        if ($bmcEntity->getProfit() === 0.0) {
            return 0.0;
        }

        return $bmcEntity->getProfit() / $bmcEntity->getTotalWeightResources();
    }

    public static function calculateCraftingGrade(BlackMarketCraftingEntity $bmcEntity): string
    {
        $quotient = $bmcEntity->getWeightProfitQuotient();
        return match (true) {
            $quotient >= 1800 => 'S',
            $quotient >= 900 => 'A',
            $quotient >= 350 => 'B',
            $quotient >= 0 => 'C',
            default => 'D',
        };
    }
}