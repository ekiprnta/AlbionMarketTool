<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\ListDataEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\RawResourceRepository;
use MZierdt\Albion\repositories\ResourceRepository;

class ListDataService
{
    public function __construct(
        private readonly ItemRepository $itemRepository,
        private readonly ResourceRepository $resourceRepository,
        private readonly RawResourceRepository $rawResourceRepository,

    ) {
    }

    public function getAllItems(): array
    {
        $fortSterlingItems = $this->itemRepository->getItemsByLocation('Fort Sterling');
        $lymhurstItems = $this->itemRepository->getItemsByLocation('Lymhurst');
        $bridgewatchItems = $this->itemRepository->getItemsByLocation('Bridgewatch');
        $martlockItems = $this->itemRepository->getItemsByLocation('Martlock');
        $thetfordItems = $this->itemRepository->getItemsByLocation('Thetford');

        $allItems = [];
        foreach ($fortSterlingItems as $fortSterlingItem) {
            $allItems[] = new ListDataEntity($fortSterlingItem);
        }

        /** @var ListDataEntity $item */
        foreach ($allItems as $item) {
            $item->setLymhurstObject($this->calculateSameItemObject($item, $lymhurstItems));
            $item->setBridgewatchObject($this->calculateSameItemObject($item, $bridgewatchItems));
            $item->setMartlockObject($this->calculateSameItemObject($item, $martlockItems));
            $item->setThetfordObject($this->calculateSameItemObject($item, $thetfordItems));

            $item->setCheapestObjectCitySellOrder(
                $this->calculateCheapestCity(
                    $item->getFortsterlingObject()->getSellOrderPrice(),
                    $item->getLymhurstObject()->getSellOrderPrice(),
                    $item->getBridgewatchObject()->getSellOrderPrice(),
                    $item->getMartlockObject()->getSellOrderPrice(),
                    $item->getThetfordObject()->getSellOrderPrice()
                )
            );
            $item->setCheapestObjectCityBuyOrder(
                $this->calculateCheapestCity(
                    $item->getFortsterlingObject()->getBuyOrderPrice(),
                    $item->getLymhurstObject()->getBuyOrderPrice(),
                    $item->getBridgewatchObject()->getBuyOrderPrice(),
                    $item->getMartlockObject()->getBuyOrderPrice(),
                    $item->getThetfordObject()->getBuyOrderPrice()
                )
            );
            $item->setMostExpensiveObjectCitySellOrder(
                $this->calculateMostExpensiveCity(
                    $item->getFortsterlingObject()->getSellOrderPrice(),
                    $item->getLymhurstObject()->getSellOrderPrice(),
                    $item->getBridgewatchObject()->getSellOrderPrice(),
                    $item->getMartlockObject()->getSellOrderPrice(),
                    $item->getThetfordObject()->getSellOrderPrice()
                )
            );
            $item->setMostExpensiveObjectCityBuyOrder(
                $this->calculateMostExpensiveCity(
                    $item->getFortsterlingObject()->getBuyOrderPrice(),
                    $item->getLymhurstObject()->getBuyOrderPrice(),
                    $item->getBridgewatchObject()->getBuyOrderPrice(),
                    $item->getMartlockObject()->getBuyOrderPrice(),
                    $item->getThetfordObject()->getBuyOrderPrice()
                )
            );
        }

        return $allItems;
    }

    private function calculateSameItemObject(
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

    private function calculateCheapestCity(
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

    private function calculateMostExpensiveCity(
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