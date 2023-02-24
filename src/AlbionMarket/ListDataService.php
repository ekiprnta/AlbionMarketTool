<?php

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntities\ListDataEntity;
use MZierdt\Albion\Entity\ResourceEntity;

class ListDataService extends Market
{
    public function calculateSameItemObject(ListDataEntity $ldEntity, array $cityObjects): ResourceEntity|null
    {
        /** @var ResourceEntity $cityObject */
        foreach ($cityObjects as $cityObject) {
            if ($cityObject->getTier() === $ldEntity->getFortsterlingObject()->getTier() &&
                $cityObject->getName() === $ldEntity->getFortsterlingObject()
                    ->getName()
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

    public function calculateListDataEntity(
        ListDataEntity $listDataEntity,
        array $lymhurstResources,
        array $bridgewatchResources,
        array $martlockResources,
        array $thetfordResources,
        string $type
    ): ListDataEntity {
        $listDataEntity->setLymhurstObject(
            $this->calculateSameItemObject($listDataEntity, $lymhurstResources)
        );
        $listDataEntity->setBridgewatchObject(
            $this->calculateSameItemObject($listDataEntity, $bridgewatchResources)
        );
        $listDataEntity->setMartlockObject(
            $this->calculateSameItemObject($listDataEntity, $martlockResources)
        );
        $listDataEntity->setThetfordObject(
            $this->calculateSameItemObject($listDataEntity, $thetfordResources)
        );

        $fortsterlingResource = $listDataEntity->getFortsterlingObject();
        $lymhurstResource = $listDataEntity->getLymhurstObject();
        $bridgewatchResource = $listDataEntity->getBridgewatchObject();
        $martlockResource = $listDataEntity->getMartlockObject();
        $thetfordResource = $listDataEntity->getThetfordObject();
        $listDataEntity->setCheapestObjectCitySellOrder(
            $this->calculateCheapestCity(
                $fortsterlingResource
                    ->getSellOrderPrice(),
                $lymhurstResource
                    ->getSellOrderPrice(),
                $bridgewatchResource
                    ->getSellOrderPrice(),
                $martlockResource
                    ->getSellOrderPrice(),
                $thetfordResource
                    ->getSellOrderPrice()
            )
        );
        $listDataEntity->setCheapestObjectCityBuyOrder(
            $this->calculateCheapestCity(
                $fortsterlingResource
                    ->getBuyOrderPrice(),
                $lymhurstResource
                    ->getBuyOrderPrice(),
                $bridgewatchResource
                    ->getBuyOrderPrice(),
                $martlockResource
                    ->getBuyOrderPrice(),
                $thetfordResource
                    ->getBuyOrderPrice()
            )
        );
        $listDataEntity->setMostExpensiveObjectCitySellOrder(
            $this->calculateMostExpensiveCity(
                $fortsterlingResource
                    ->getSellOrderPrice(),
                $lymhurstResource
                    ->getSellOrderPrice(),
                $bridgewatchResource
                    ->getSellOrderPrice(),
                $martlockResource
                    ->getSellOrderPrice(),
                $thetfordResource
                    ->getSellOrderPrice()
            )
        );
        $listDataEntity->setMostExpensiveObjectCityBuyOrder(
            $this->calculateMostExpensiveCity(
                $fortsterlingResource
                    ->getBuyOrderPrice(),
                $lymhurstResource
                    ->getBuyOrderPrice(),
                $bridgewatchResource
                    ->getBuyOrderPrice(),
                $martlockResource
                    ->getBuyOrderPrice(),
                $thetfordResource
                    ->getBuyOrderPrice()
            )
        );
        $listDataEntity->setType($type);

        return $listDataEntity;
    }
}
