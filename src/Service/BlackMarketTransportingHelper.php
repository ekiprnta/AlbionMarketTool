<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ItemEntity;

class BlackMarketTransportingHelper extends Market
{
    public function calculateCityItem(ItemEntity $bmItem, array $Items): ItemEntity
    {
        /** @var ItemEntity $item */
        foreach ($Items as $item) {
            if ($item->getTier() === $bmItem->getTier() &&
                $item->getName() === $bmItem->getName()) {
                return $item;
            }
        }
        throw new \RuntimeException('No Item found for ' . $bmItem->getName());
    }

    public function calculateProfit(float $singleProfit, int $amount): float
    {
        return $singleProfit * $amount;
    }

    public function calculateAmount(int $totalWeight, float $itemWeight): int
    {
        return (int) ($totalWeight / $itemWeight);
    }

    public function calculateSingleProfit(int $bmPrice, int $cityPrice): float
    {
        return $this->calculateSellOrder($bmPrice) - $cityPrice;
    }
}
