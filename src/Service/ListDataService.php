<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ListDataEntity;
use MZierdt\Albion\repositories\RawResourceRepository;
use MZierdt\Albion\repositories\ResourceRepository;

class ListDataService
{
    public function __construct(
        private readonly ResourceRepository $resourceRepository,
        private readonly RawResourceRepository $rawResourceRepository,
        private readonly ListDataHelper $listDataHelper,
    ) {
    }

    public function getAllRawResources(): array
    {
        $fortSterlingItems = $this->rawResourceRepository->getRawResourcesByCity('Fort Sterling');
        $lymhurstItems = $this->rawResourceRepository->getRawResourcesByCity('Lymhurst');
        $bridgewatchItems = $this->rawResourceRepository->getRawResourcesByCity('Bridgewatch');
        $martlockItems = $this->rawResourceRepository->getRawResourcesByCity('Martlock');
        $thetfordItems = $this->rawResourceRepository->getRawResourcesByCity('Thetford');

        return $this->getListDataEntities(
            $fortSterlingItems,
            $lymhurstItems,
            $bridgewatchItems,
            $martlockItems,
            $thetfordItems
        );
    }

    public function getAllResources(): array
    {
        $fortSterlingItems = $this->resourceRepository->getResourcesByCity('Fort Sterling');
        $lymhurstItems = $this->resourceRepository->getResourcesByCity('Lymhurst');
        $bridgewatchItems = $this->resourceRepository->getResourcesByCity('Bridgewatch');
        $martlockItems = $this->resourceRepository->getResourcesByCity('Martlock');
        $thetfordItems = $this->resourceRepository->getResourcesByCity('Thetford');

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
                    $item->getFortsterlingObject()->getSellOrderPrice(),
                    $item->getLymhurstObject()->getSellOrderPrice(),
                    $item->getBridgewatchObject()->getSellOrderPrice(),
                    $item->getMartlockObject()->getSellOrderPrice(),
                    $item->getThetfordObject()->getSellOrderPrice()
                )
            );
            $item->setCheapestObjectCityBuyOrder(
                $this->listDataHelper->calculateCheapestCity(
                    $item->getFortsterlingObject()->getBuyOrderPrice(),
                    $item->getLymhurstObject()->getBuyOrderPrice(),
                    $item->getBridgewatchObject()->getBuyOrderPrice(),
                    $item->getMartlockObject()->getBuyOrderPrice(),
                    $item->getThetfordObject()->getBuyOrderPrice()
                )
            );
            $item->setMostExpensiveObjectCitySellOrder(
                $this->listDataHelper->calculateMostExpensiveCity(
                    $item->getFortsterlingObject()->getSellOrderPrice(),
                    $item->getLymhurstObject()->getSellOrderPrice(),
                    $item->getBridgewatchObject()->getSellOrderPrice(),
                    $item->getMartlockObject()->getSellOrderPrice(),
                    $item->getThetfordObject()->getSellOrderPrice()
                )
            );
            $item->setMostExpensiveObjectCityBuyOrder(
                $this->listDataHelper->calculateMostExpensiveCity(
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

}