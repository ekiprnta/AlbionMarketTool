<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use DateTimeImmutable;
use InvalidArgumentException;
use MZierdt\Albion\Entity\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\ItemRepository;

class BlackMarketTransportingService
{
    public function __construct(private ItemRepository $itemRepository)
    {
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
            $bmtEntity->setCityItem(BlackMarketTransportingHelper::calculateCityItem($bmtEntity, $cityItem));
            $bmtEntity->setProfit(BlackMarketTransportingHelper::calculateProfit($bmtEntity, 'Fort Sterling'));
            $bmtEntity->setWeightProfitQuotient(BlackMarketTransportingHelper::calculateWeightProfitQuotient($bmtEntity->getProfit(), $weight));
            $bmtEntity->setProfitGrade(BlackMarketTransportingHelper::calculateProfitGrade($bmtEntity->getWeightProfitQuotient()));

        }
//        $combinedItems = $this->combineItems($cityItems, $bmItems);
        return $this->filterItems($bmtEntities, $tierList);
    }

    private function filterItems(array $bmtEntities, array $tierList): array
    {
        /** @var BlackMarketTransportEntity $bmtEntity */
        foreach ($bmtEntities as $key => $bmtEntity) {
            if (! in_array($bmtEntity->getBmItem()->getTier(), $tierList, true)) {
                unset($bmtEntities[$key]);
            }
        }
        return $bmtEntities;
    }
}
