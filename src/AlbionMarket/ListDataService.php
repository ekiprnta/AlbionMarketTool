<?php

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntitites\ListDataEntity;
use MZierdt\Albion\repositories\ResourceRepository;

class ListDataService
{
    public function __construct(
        private readonly ResourceRepository $resourceRepository,
        private readonly ListDataHelper $listDataHelper,
    ) {
    }

    public function getResources(string $type): array
    {
        if ($type === 'rawResource') {
            $fortSterlingItems = $this->resourceRepository->getRawResourcesByCity('Fort Sterling');
            $lymhurstItems = $this->resourceRepository->getRawResourcesByCity('Lymhurst');
            $bridgewatchItems = $this->resourceRepository->getRawResourcesByCity('Bridgewatch');
            $martlockItems = $this->resourceRepository->getRawResourcesByCity('Martlock');
            $thetfordItems = $this->resourceRepository->getRawResourcesByCity('Thetford');
        } else {
            if ($type === 'resource') {
                $fortSterlingItems = $this->resourceRepository->getResourcesByCity('Fort Sterling');
                $lymhurstItems = $this->resourceRepository->getResourcesByCity('Lymhurst');
                $bridgewatchItems = $this->resourceRepository->getResourcesByCity('Bridgewatch');
                $martlockItems = $this->resourceRepository->getResourcesByCity('Martlock');
                $thetfordItems = $this->resourceRepository->getResourcesByCity('Thetford');
            } else {
                throw new \InvalidArgumentException('Wrong Type int getResources ' . $type);
            }
        }

        return $this->getListDataEntities(
            $fortSterlingItems,
            $lymhurstItems,
            $bridgewatchItems,
            $martlockItems,
            $thetfordItems
        );
    }

    private function getListDataEntities(
        array $fortSterlingItems,
        array $lymhurstItems,
        array $bridgewatchItems,
        array $martlockItems,
        array $thetfordItems
    ): array {
        $allItems = [];
        foreach ($fortSterlingItems as $fortSterlingItem) {
            $allItems[] = new ListDataEntity($fortSterlingItem);
        }

        /** @var ListDataEntity $item */
        foreach ($allItems as $item) {
            $item->setLymhurstObject($this->listDataHelper->calculateSameItemObject($item, $lymhurstItems));
            $item->setBridgewatchObject($this->listDataHelper->calculateSameItemObject($item, $bridgewatchItems));
            $item->setMartlockObject($this->listDataHelper->calculateSameItemObject($item, $martlockItems));
            $item->setThetfordObject($this->listDataHelper->calculateSameItemObject($item, $thetfordItems));

            $item->setCheapestObjectCitySellOrder(
                $this->listDataHelper->calculateCheapestCity(
                    $item->getFortsterlingObject()
                        ->getSellOrderPrice(),
                    $item->getLymhurstObject()
                        ->getSellOrderPrice(),
                    $item->getBridgewatchObject()
                        ->getSellOrderPrice(),
                    $item->getMartlockObject()
                        ->getSellOrderPrice(),
                    $item->getThetfordObject()
                        ->getSellOrderPrice()
                )
            );
            $item->setCheapestObjectCityBuyOrder(
                $this->listDataHelper->calculateCheapestCity(
                    $item->getFortsterlingObject()
                        ->getBuyOrderPrice(),
                    $item->getLymhurstObject()
                        ->getBuyOrderPrice(),
                    $item->getBridgewatchObject()
                        ->getBuyOrderPrice(),
                    $item->getMartlockObject()
                        ->getBuyOrderPrice(),
                    $item->getThetfordObject()
                        ->getBuyOrderPrice()
                )
            );
            $item->setMostExpensiveObjectCitySellOrder(
                $this->listDataHelper->calculateMostExpensiveCity(
                    $item->getFortsterlingObject()
                        ->getSellOrderPrice(),
                    $item->getLymhurstObject()
                        ->getSellOrderPrice(),
                    $item->getBridgewatchObject()
                        ->getSellOrderPrice(),
                    $item->getMartlockObject()
                        ->getSellOrderPrice(),
                    $item->getThetfordObject()
                        ->getSellOrderPrice()
                )
            );
            $item->setMostExpensiveObjectCityBuyOrder(
                $this->listDataHelper->calculateMostExpensiveCity(
                    $item->getFortsterlingObject()
                        ->getBuyOrderPrice(),
                    $item->getLymhurstObject()
                        ->getBuyOrderPrice(),
                    $item->getBridgewatchObject()
                        ->getBuyOrderPrice(),
                    $item->getMartlockObject()
                        ->getBuyOrderPrice(),
                    $item->getThetfordObject()
                        ->getBuyOrderPrice()
                )
            );
        }

        return $allItems;
    }
}
