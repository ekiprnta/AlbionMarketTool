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

    public static function calculateProfit(BlackMarketTransportEntity $bmtEntity, string $city)
    {
        $amount = $bmtEntity->getWeight() / $bmtEntity->getBmItem()->getWeight();
        $cityItem = self::getCityItem($bmtEntity, $city);

        $singleProfit = $bmtEntity->getBmItem()->getSellOrderPrice()* (1- parent::MARKET_FEE - parent::MARKET_SETUP) - $cityItem->getSellOrderPrice();
        $profit = $singleProfit * $amount;

        $weightProfitQuotient = $profit / $bmtEntity->getWeight();


        return [
            'amount' => $amount,
            'singleProfit'
            'profit'
            'quotient'
            'colorGrade'
        ]
    }

    private static function getCityItem(BlackMarketTransportEntity $bmtEntity, string $city): ItemEntity
    {
        return match ($city) {
            'Fort Sterling' => $bmtEntity->getFsItem(),
            'Lymhurst' => $bmtEntity->getLymItem(),
            'Bridgewatch' => $bmtEntity->getBwItem(),
            'Martlock' => $bmtEntity->getMlItem(),
            'Thetford' => $bmtEntity->getThItem(),
        };
    }
}