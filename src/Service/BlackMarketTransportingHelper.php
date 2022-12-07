<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;

class BlackMarketTransportingHelper implements MarketInterface
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
}