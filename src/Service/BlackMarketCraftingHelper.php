<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;

class BlackMarketCraftingHelper extends Market
{
    public function calculateResources(string $resourceName,string $tier, array $resources): ?ResourceEntity
    {
        /** @var ResourceEntity $resource */
        foreach ($resources as $resource) {
            if (($tier === $resource->getTier()) && $resource->getRealName() === $resourceName) {
                return $resource;
            }
        }
        return null;
    }


    public function calculateJournals(BlackMarketCraftingEntity $bmcEntity, array $journals): array
    {
        $tier = $bmcEntity->getItem()
            ->getTier();

        $journalAmountPerItem = null;
        $emptyJournal = null;
        $fullJournal = null;
        /** @var JournalEntity $journal */
        foreach ($journals as $journal) {
            if ($tier[0] === $journal->getTier()) {
                if ($journal->getFillStatus() === 'empty') {
                    $journalAmountPerItem = $bmcEntity->getItem()
                        ->getFame() / $journal->getFameToFill();
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

    public function calculateFameAmount(BlackMarketCraftingEntity $bmcEntity): float
    {
        return $bmcEntity->getTotalAmount() * $bmcEntity->getItem()
            ->getFame() * self::PREMIUM_FACTOR;
    }

    public function calculateTotalAmount(BlackMarketCraftingEntity $bmcEntity, int $weight): array
    {
        $resourceWeightForItem = $bmcEntity->getPrimResource()
            ->getWeight() *
            ($bmcEntity->getItem()->getPrimaryResourceAmount() +
                $bmcEntity->getItem()
                    ->getSecondaryResourceAmount());
        $journalWeightForItem = $bmcEntity->getJournalEntityEmpty()
            ->getWeight() *
            $bmcEntity->getJournalAmountPerItem();

        $totalAmount = (int) ($weight / ($resourceWeightForItem + $journalWeightForItem));

        return [
            'totalAmount' => ($totalAmount),
            'primResourceAmount' => ($totalAmount * $bmcEntity->getItem()->getPrimaryResourceAmount()),
            'secResourceAmount' => ($totalAmount * $bmcEntity->getItem()->getSecondaryResourceAmount()),
            'journalAmount' => ((int) ceil($totalAmount * $bmcEntity->getJournalAmountPerItem())),
            'totalItemWeight' => ($totalAmount * $bmcEntity->getItem()->getWeight()),
        ];
    }


    public function calculateCraftingFee(
        BlackMarketCraftingEntity $bmcEntity,
        int $feeProHundredNutrition
    ): float {
        $nutrition = $bmcEntity->getItem()
            ->getItemValue() * self::NUTRITION_FACTOR;
        return $nutrition * $feeProHundredNutrition / 100;
    }

    public function calculateProfit(
        BlackMarketCraftingEntity $bmcEntity,
        float $percentage,
        string $order,
    ): array {
        if ($order === '1') {
            $itemCost = $bmcEntity->getPrimResource()
                ->getSellOrderPrice() *
                $bmcEntity->getItem()
                    ->getPrimaryResourceAmount() +
                $bmcEntity->getSecResource()
                    ->getSellOrderPrice() *
                $bmcEntity->getItem()
                    ->getSecondaryResourceAmount();

            $primAge = $bmcEntity->getPrimResource()
                ->getSellOrderAge();
            $secAge = $bmcEntity->getSecResource()
                ->getSellOrderAge();
        } else {
            $itemCost = $this->calculateBuyOrder(
                $bmcEntity->getPrimResource()->getBuyOrderPrice() *
                $bmcEntity->getItem()
                    ->getPrimaryResourceAmount() +
                $bmcEntity->getSecResource()
                    ->getBuyOrderPrice() *
                $bmcEntity->getItem()
                    ->getSecondaryResourceAmount()
            );

            $primAge = $bmcEntity->getPrimResource()
                ->getBuyOrderAge();
            $secAge = $bmcEntity->getSecResource()
                ->getBuyOrderAge();
        }

        $profit = $this->calculateProfitByPercentage($bmcEntity, $itemCost, $percentage);
        $craftingFee = $bmcEntity->getCraftingFee();
        $profitJournals = $bmcEntity->getProfitBooks();

        return [
            'profit' => $profit - $craftingFee + $profitJournals,
            'primAge' => $primAge,
            'secAge' => $secAge,
        ];
    }

    public function calculateProfitBooks(BlackMarketCraftingEntity $bmcEntity): float
    {
        return ($this->calculateSellOrder($bmcEntity->getJournalEntityFull()->getSellOrderPrice()) -
                $bmcEntity->getJournalEntityEmpty()
                    ->getBuyOrderPrice()) *
            $bmcEntity->getJournalAmount();
    }

    public function calculateProfitByPercentage(
        BlackMarketCraftingEntity $bmcEntity,
        float $itemCost,
        float $percentage
    ): float {
        $rate = (self::RRR_BASE_PERCENTAGE - $percentage) / 100;
        $amount = $bmcEntity->getTotalAmount();
        $itemSellPrice = $this->calculateSellOrder($bmcEntity->getItem()->getSellOrderPrice());
        return ($itemSellPrice - ($itemCost * $rate)) * $amount;
    }

    public function calculateItemValue(BlackMarketCraftingEntity $bmcEntity): int
    {
        return $bmcEntity->getItem()
            ->getSellOrderPrice() * $bmcEntity->getTotalAmount();
    }
}
