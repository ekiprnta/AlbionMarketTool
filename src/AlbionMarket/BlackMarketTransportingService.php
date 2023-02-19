<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\ItemEntity;

class BlackMarketTransportingService extends Market
{
    public function __construct()
    {
    }

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

    public function calculateAmount(int $primAmount, int $secAmount, array $amountConfig): int
    {
        $totalAmount = $primAmount + $secAmount;
        return $amountConfig[$totalAmount];
    }

    public function calculateProfit(int $bmPrice, int $cityPrice): float
    {
        return $this->calculateSellOrder($bmPrice) - $cityPrice;
    }
}
