<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;

class BlackMarketTransportingHelper extends Market
{
    public static function calculateCityItem(BlackMarketTransportEntity $bmtEntity, array $Items): ItemEntity
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

    public static function calculateProfit(BlackMarketTransportEntity $bmtEntity): array
    {
        $amount = $bmtEntity->getWeight() / $bmtEntity->getBmItem()
            ->getWeight();

        $singleProfit = parent::calculateSellOrder(
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

    public static function calculateWeightProfitQuotient(float|int $profit, int $weight): float
    {
        return parent::calculateWeightProfitQuotient($profit, $weight);
    }

    public static function calculateProfitGrade(float $quotient): string
    {
        return parent::calculateProfitGrade($quotient);
    }

    private static function getCityItem(BlackMarketTransportEntity $bmtEntity, string $city): ItemEntity
    {
        return match ($city) {
            'Fort Sterling' => $bmtEntity->getCityItem(),
            'Lymhurst' => $bmtEntity->getLymItem(),
            'Bridgewatch' => $bmtEntity->getBwItem(),
            'Martlock' => $bmtEntity->getMlItem(),
            'Thetford' => $bmtEntity->getThItem(),
        };
    }
}
