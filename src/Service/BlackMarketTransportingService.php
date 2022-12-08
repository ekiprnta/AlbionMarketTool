<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
use MZierdt\Albion\Entity\BlackMarketTransportEntity;
use MZierdt\Albion\repositories\ItemRepository;

class BlackMarketTransportingService
{
    public function __construct(
        private ItemRepository $itemRepository,
        private BlackMarketTransportingHelper $bmtHelper
    ) {
    }

    public function getDataForCity(string $itemCity, int $weight, array $tierList): array
    {
        if (empty($tierList)) {
            throw new InvalidArgumentException('No Tiers selected');
        }
        if (empty($itemCity)) {
            throw new InvalidArgumentException('Please select a city');
        }
        if (empty($weight)) {
            throw new InvalidArgumentException('Please insert your maximum carry Weight');
        }
        $cityItem = $this->itemRepository->getItemsForTransport($itemCity);
        $bmItems = $this->itemRepository->getItemsForTransport('Black Market');

        $bmtEntities = [];
        foreach ($bmItems as $bmItem) {
            $bmtEntities[] = new BlackMarketTransportEntity($bmItem, $weight);
        }

        /** @var BlackMarketTransportEntity $bmtEntity */
        foreach ($bmtEntities as $bmtEntity) {
            $bmtEntity->setCityItem($this->bmtHelper->calculateCityItem($bmtEntity->getBmItem(), $cityItem));
            $bmtEntity->setAmount($this->bmtHelper->calculateAmount($bmtEntity->getWeight(), $bmtEntity->getBmItem()->getWeight()));
            $bmtEntity->setSingleProfit($this->bmtHelper->calculateSingleProfit($bmtEntity->getBmItem()->getSellOrderPrice(), $bmtEntity->getCityItem()->getSellOrderPrice()));
            $bmtEntity->setProfit($this->bmtHelper->calculateProfit($bmtEntity->getSingleProfit(), $bmtEntity->getAmount()));
            $bmtEntity->setWeightProfitQuotient(
                $this->bmtHelper->calculateWeightProfitQuotient($bmtEntity->getProfit(), $weight)
            );
            $bmtEntity->setProfitGrade(
                $this->bmtHelper->calculateProfitGrade($bmtEntity->getWeightProfitQuotient())
            );
        }
//        $combinedItems = $this->combineItems($cityItems, $bmItems);
        return $this->filterItems($bmtEntities, $tierList);
    }

    private function filterItems(array $bmtEntities, array $tierList): array
    {
        /** @var BlackMarketTransportEntity $bmtEntity */
        foreach ($bmtEntities as $key => $bmtEntity) {
            if (!in_array($bmtEntity->getBmItem()->getTier(), $tierList, true)) {
                unset($bmtEntities[$key]);
            }
        }
        return $bmtEntities;
    }
}
