<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;

class BlackMarketTransportingHelper extends Market
{
    public function calculateCityItem(BlackMarketTransportEntity $bmtEntity, array $Items): ItemEntity
    {
        $bmItem = $bmtEntity->getBmItem();
        /** @var ItemEntity $item */
        foreach ($Items as $item) {
            if ($item->getTier() === $bmItem->getTier() &&
                $item->getName() === $bmItem->getName()) {
                return $item;
            }
        }
        throw new \RuntimeException('No Item found for ' . $bmItem->getRealName());
    }

    public function calculateProfit(float $singleProfit, int $amount): float
    {
        return $singleProfit * $amount;
    }

    public function calculateAmount(int $totalWeight, float $itemWeight): int
    {
        return (int)($totalWeight / $itemWeight);
    }

    public function calculateSingleProfit(int $bmPrice, int $cityPrice): float
    {
        return $this->calculateSellOrder($bmPrice) - $cityPrice;
    }
}
