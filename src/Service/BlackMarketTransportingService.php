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
        $this->maxWeight = $weight;
        $fsItems = $this->itemRepository->getItemsForTransport('Fort Sterling');
        $lymItems = $this->itemRepository->getItemsForTransport('Lymhurst');
        $bwItems = $this->itemRepository->getItemsForTransport('Bridgewatch');
        $mlItems = $this->itemRepository->getItemsForTransport('Martlock');
        $thItems = $this->itemRepository->getItemsForTransport('Thetford');
        $bmItems = $this->itemRepository->getItemsForTransport('Black Market');

        $bmtEntities = [];
        foreach ($bmItems as $bmItem) {
            $bmtEntities[] = new BlackMarketTransportEntity($bmItem, $weight);
        }

        /** @var BlackMarketTransportEntity $bmtEntity */
        foreach ($bmtEntities as $bmtEntity) {
            $bmtEntity->setFsItem(BlackMarketTransportingHelper::calculateCityItem($bmtEntity, $fsItems));
            $bmtEntity->setLymItem(BlackMarketTransportingHelper::calculateCityItem($bmtEntity, $lymItems));
            $bmtEntity->setBwItem(BlackMarketTransportingHelper::calculateCityItem($bmtEntity, $bwItems));
            $bmtEntity->setMlItem(BlackMarketTransportingHelper::calculateCityItem($bmtEntity, $mlItems));
            $bmtEntity->setThItem(BlackMarketTransportingHelper::calculateCityItem($bmtEntity, $thItems));


            $bmtEntity->setFsProfit(BlackMarketTransportingHelper::calculateProfit($bmtEntity, 'Fort Sterling'));
            $bmtEntity->setLymProfit(BlackMarketTransportingHelper::calculateProfit($bmtEntity, 'Lymhurst'));
            $bmtEntity->setBwProfit(BlackMarketTransportingHelper::calculateProfit($bmtEntity, 'Bridgewatch'));
            $bmtEntity->setMlProfit(BlackMarketTransportingHelper::calculateProfit($bmtEntity, 'Martlock'));
            $bmtEntity->setThProfit(BlackMarketTransportingHelper::calculateProfit($bmtEntity, 'Thetford'));

            $bmtEntity->setFsWeightProfitQuotient(BlackMarketTransportingHelper::calculateWeightProfitQuotient($bmtEntity->getFsProfit(), $weight));
            $bmtEntity->setLymWeightProfitQuotient(BlackMarketTransportingHelper::calculateWeightProfitQuotient($bmtEntity->getLymProfit(), $weight));
            $bmtEntity->setBwWeightProfitQuotient(BlackMarketTransportingHelper::calculateWeightProfitQuotient($bmtEntity->getBwProfit(), $weight));
            $bmtEntity->setMlWeightProfitQuotient(BlackMarketTransportingHelper::calculateWeightProfitQuotient($bmtEntity->getMlProfit(), $weight));
            $bmtEntity->setThWeightProfitQuotient(BlackMarketTransportingHelper::calculateWeightProfitQuotient($bmtEntity->getThProfit(), $weight));

            $bmtEntity->setFsProfitGrade(BlackMarketTransportingHelper::calculateProfitGrade($bmtEntity->getFsWeightProfitQuotient()));
            $bmtEntity->setLymProfitGrade(BlackMarketTransportingHelper::calculateProfitGrade($bmtEntity->getFsWeightProfitQuotient()));
            $bmtEntity->setBwProfitGrade(BlackMarketTransportingHelper::calculateProfitGrade($bmtEntity->getFsWeightProfitQuotient()));
            $bmtEntity->setMlProfitGrade(BlackMarketTransportingHelper::calculateProfitGrade($bmtEntity->getFsWeightProfitQuotient()));
            $bmtEntity->setThProfitGrade(BlackMarketTransportingHelper::calculateProfitGrade($bmtEntity->getFsWeightProfitQuotient()));

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
