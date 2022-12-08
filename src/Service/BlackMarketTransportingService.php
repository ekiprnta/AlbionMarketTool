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
    private int $maxWeight;

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

            $bmtEntity->setFsWeightProfitQuotient(BlackMarketTransportingHelper::calculateWeightProfitQuotient($bmtEntity->getFsProfit(), $weight))
            $bmtEntity->setLymWeightProfitQuotient(BlackMarketTransportingHelper::calculateWeightProfitQuotient($bmtEntity->getLymProfit(), $weight))
            $bmtEntity->setBwWeightProfitQuotient(BlackMarketTransportingHelper::calculateWeightProfitQuotient($bmtEntity->getBwProfit(), $weight))
            $bmtEntity->setMlWeightProfitQuotient(BlackMarketTransportingHelper::calculateWeightProfitQuotient($bmtEntity->getMlProfit(), $weight))
            $bmtEntity->setThWeightProfitQuotient(BlackMarketTransportingHelper::calculateWeightProfitQuotient($bmtEntity->getThProfit(), $weight))
        }


//        $combinedItems = $this->combineItems($cityItems, $bmItems);
        return $this->filterItems($bmtEntities, $tierList);
    }

    private function combineItems(array $cityItems, array $bmItems): array
    {
        $transportList = [];
        /** @var ItemEntity $bmItem */
        foreach ($bmItems as $bmItem) {
            $transportList[$bmItem->getName() . '#' . $bmItem->getTier()] = new BlackMarketTransportEntity($bmItem);
            $this->addCityItems($transportList[$bmItem->getName() . '#' . $bmItem->getTier()], $cityItems);
        }
        ksort($transportList);
        return $transportList;
    }

    private function calculateColorGrade(?float $quotient): string
    {
        if ($quotient === null) {
            return 'D';
        }
        return match (true) {
            $quotient >= 10000 => 'S',
            $quotient >= 5000 => 'A',
            $quotient >= 1000 => 'B',
            $quotient >= 500 => 'C',
            default => 'D',
        };
    }

    private function addCityItems(BlackMarketTransportEntity $transportEntity, array $cityItems): void
    {
        /** @var ItemEntity $cityItem */
        foreach ($cityItems as $cityItem) {
            if ($cityItem->getTier() === $transportEntity->getTier() &&
                $cityItem->getName() === $transportEntity->getName()) {
                $transportEntity->setCityPrice($cityItem->getSellOrderPrice());
                $transportEntity->setCityPriceDate($cityItem->getSellOrderPriceDate());
                $transportEntity->setAmount((int) ceil($this->maxWeight / $transportEntity->getWeight()));
                $transportEntity->setCityProfit(
                    (int) ($transportEntity->getBmPrice() *
                        (1 - BlackMarketCraftingService::MARKET_SETUP - BlackMarketCraftingService::MARKET_FEE) -
                        $transportEntity->getCityPrice())
                );
                $transportEntity->setCityWeightProfitQuotient(
                    $transportEntity->getCityProfit() / $transportEntity->getWeight()
                );
                $transportEntity->setCityColorGrade(
                    $this->calculateColorGrade($transportEntity->getCityWeightProfitQuotient())
                );
                $transportEntity->setCityPriceAge($this->getTimeDiff($transportEntity->getCityPriceDate()));
                $transportEntity->setBmPriceAge($this->getTimeDiff($transportEntity->getBmPriceDate()));
                $transportEntity->setTotalProfit($transportEntity->getAmount() * $transportEntity->getCityProfit());
            }
        }
    }

    private function getTimeDiff(?DateTimeImmutable $priceDate): int
    {
        if ($priceDate === null) {
            return 999999;
        }
        $now = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', Date('Y-m-d H:i:s'));
        $itemDiff = date_diff($now, $priceDate);
        return $itemDiff->d * 24 * 60 + $itemDiff->h * 60 + $itemDiff->i;
    }

    private function filterItems(array $combinedItems, array $tierList): array
    {
        /** @var BlackMarketTransportEntity $combinedItem */
        foreach ($combinedItems as $key => $combinedItem) {
            if (! in_array($combinedItem->getTier(), $tierList, true)) {
                unset($combinedItems[$key]);
            }
        }
        return $combinedItems;
    }
}
