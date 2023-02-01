<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\ListDataEntity;
use MZierdt\Albion\Entity\ResourceEntity;

class ListDataHelper extends Market
{
    public function calculateSameItemObject(
        ListDataEntity $ldEntity,
        array $cityObjects
    ): ResourceEntity|ItemEntity|null {
        /** @var ItemEntity|ResourceEntity $cityObject */
        foreach ($cityObjects as $cityObject) {
            if ($cityObject->getTier() === $ldEntity->getFortsterlingObject()->getTier() &&
                $cityObject->getName() === $ldEntity->getFortsterlingObject()->getName()
            ) {
                return $cityObject;
            }
        }
        return null;
    }

    public function calculateCheapestCity(
        int $fortSterlingPrice,
        int $lymhurstPrice,
        int $bridgewatchPrice,
        int $martlockPrice,
        int $thetfordPrice
    ): string {
        $cheapestPrice = $fortSterlingPrice;
        $city = 'Fort Sterling';
        if ($cheapestPrice > $lymhurstPrice) {
            $cheapestPrice = $lymhurstPrice;
            $city = 'Lymhurst';
        }
        if ($cheapestPrice > $bridgewatchPrice) {
            $cheapestPrice = $bridgewatchPrice;
            $city = 'Bridgewatch';
        }
        if ($cheapestPrice > $martlockPrice) {
            $cheapestPrice = $martlockPrice;
            $city = 'Martlock';
        }
        if ($cheapestPrice > $thetfordPrice) {
            $city = 'Thetford';
        }
        return $city;
    }

    public function calculateMostExpensiveCity(
        int $fortSterlingPrice,
        int $lymhurstPrice,
        int $bridgewatchPrice,
        int $martlockPrice,
        int $thetfordPrice
    ): string {
        $cheapestPrice = $fortSterlingPrice;
        $city = 'Fort Sterling';
        if ($cheapestPrice < $lymhurstPrice) {
            $cheapestPrice = $lymhurstPrice;
            $city = 'Lymhurst';
        }
        if ($cheapestPrice < $bridgewatchPrice) {
            $cheapestPrice = $bridgewatchPrice;
            $city = 'Bridgewatch';
        }
        if ($cheapestPrice < $martlockPrice) {
            $cheapestPrice = $martlockPrice;
            $city = 'Martlock';
        }
        if ($cheapestPrice < $thetfordPrice) {
            $city = 'Thetford';
        }
        return $city;
    }
}