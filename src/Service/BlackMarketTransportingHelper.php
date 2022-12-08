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

    public function calculateProfit(BlackMarketTransportEntity $bmtEntity): array
    {
        $amount = $bmtEntity->getWeight() / $bmtEntity->getBmItem()
            ->getWeight();

        $singleProfit = $this->calculateSellOrder(
                $bmtEntity->getBmItem()
                    ->getSellOrderPrice()
            ) - $bmtEntity->getCityItem()
            ->getSellOrderPrice();
        $profit = $singleProfit * $amount;

        return [
            'amount' => (int) $amount,
            'singleProfit' => $singleProfit,
            'profit' => $profit,
        ];
    }
}
