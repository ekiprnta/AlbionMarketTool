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

    public function getDataForCity(string $itemCity, int $weight): array
    {
        if (empty($itemCity)) {
            throw new InvalidArgumentException('Please select a city');
        }
        if (empty($weight)) {
            throw new InvalidArgumentException('Please insert your maximum carry Weight');
        }
        $this->maxWeight = $weight;
        $cityItems = $this->itemRepository->getItemsForTransport($itemCity);
        $bmItems = $this->itemRepository->getItemsForTransport('Black Market');
        $combinedItems = $this->combineItems($cityItems, $bmItems);

        return $combinedItems;
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
        if ( $quotient === null) {
            return 'D';
        }
        return match (true) {
            $quotient >= 1000 => 'S',
            $quotient >= 400 => 'A',
            $quotient >= 100 => 'B',
            $quotient >= 0 => 'C',
            default => 'D',
        };
    }

    private function addCityItems(BlackMarketTransportEntity $transportEntity, array $cityItems): void
    {
        /** @var ItemEntity $cityItem */
        foreach ($cityItems as $cityItem) {
            if ($cityItem->getTier() === $transportEntity->getTier() &&
            $cityItem->getName() === $transportEntity->getName()){
                $transportEntity->setCityPrice($cityItem->getSellOrderPrice());
                $transportEntity->setCityPriceDate($cityItem->getSellOrderPriceDate());
                $transportEntity->setAmount((int)ceil($this->maxWeight /$transportEntity->getWeight()));
                $transportEntity->setCityProfit($transportEntity->getBmPrice() - $transportEntity->getCityPrice());
                $transportEntity->setCityWeightProfitQuotient($transportEntity->getCityProfit() / $transportEntity->getWeight());
                $transportEntity->setCityColorGrade($this->calculateColorGrade($transportEntity->getCityWeightProfitQuotient()));
                $transportEntity->setCityPriceAge($this->getTimeDiff($transportEntity->getCityPriceDate()));
                $transportEntity->setBmPriceAge($this->getTimeDiff($transportEntity->getBmPriceDate()));
                $transportEntity->setTotalProfit($transportEntity->getAmount() * $transportEntity->getCityProfit());
            }
        }
    }

    private function getTimeDiff(?DateTimeImmutable $priceDate): float|int
    {
        if ($priceDate === null) {
            return 999999;
        }
        $now = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', Date('Y-m-d H:i:s'));
        $itemDiff = date_diff($now, $priceDate);
        return $itemDiff->d * 24 * 60 + $itemDiff->h * 60 + $itemDiff->i;
    }
}
