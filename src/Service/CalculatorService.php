<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
use MZierdt\Albion\Entity\CalculateEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\ResourceRepository;

class CalculatorService
{
    private const RRR_BONUS_CITY_NO_FOCUS = 24.8;
    private const RRR_BONUS_CITY_FOCUS = 47.9;
    private const RRR_BASE_PERCENTAGE = 100;

    private int $maxWeight;

    public function __construct(
        private ItemRepository $itemRepository,
        private ResourceRepository $resourceRepository,
    ) {
    }

    public function getDataForCity(string $city, int $weight, float $percentage): array
    {
        if (empty($city)) {
            throw new InvalidArgumentException('Please select a city');
        }
        if (empty($weight)) {
            throw new InvalidArgumentException('Please insert your maximum carry Weight');
        }
        if (empty($percentage)) {
            $percentage = self::RRR_BONUS_CITY_NO_FOCUS;
        }
        $this->maxWeight = $weight;
        $items = $this->itemRepository->getItemsFromCity($city);
        $resources = $this->resourceRepository->getResourcesByCity($city);

        $calculateEntityArray = [];
        /** @var ItemEntity $item */
        foreach ($items as $item) {
            $calculateEntityArray[] = new CalculateEntity($item, $resources);
        }

        $this->calculateTotalWeight($calculateEntityArray);
        $this->calculateProfit($calculateEntityArray, $percentage);
        $this->calculateWeightProfitQuotient($calculateEntityArray);
        $this->calculateColorGrade($calculateEntityArray);
        $this->calculateAgeOfPrices($calculateEntityArray);
        $filteredArray = $this->filterCalculateEntityArray($calculateEntityArray);
//        dd($filteredArray);
        return $filteredArray;
    }

    private function filterCalculateEntityArray(array $calculateEntityArray)
    {
        $array = [];
        /** @var CalculateEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $array[$calculateEntity->getWeaponGroup() . $calculateEntity->getName()][] = $calculateEntity;
        }
        krsort($array);
        return $array;
    }

    private function calculateProfit(array $calculateEntityArray, float $percentage): void
    {
        /** @var CalculateEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $calculateEntity->setPercentageProfit($this->calculateProfitByPercentage($calculateEntity, $percentage));
        }
    }

    private function calculateProfitByPercentage(CalculateEntity $calculateEntity, float $percentage): float
    {
        $itemCost = $calculateEntity->getPrimarySellOrderPrice() *
            $calculateEntity->getPrimaryResourceAmount() +
            $calculateEntity->getSecondarySellOrderPrice() *
            $calculateEntity->getSecondaryResourceAmount();
        $rate = (self::RRR_BASE_PERCENTAGE - $percentage) / 100;
        $amount = $calculateEntity->getAmount();
        return ($calculateEntity->getItemSellOrderPrice() - ($itemCost * $rate)) * $amount;
    }

    private function calculateTotalWeight(array $calculateEntityArray): void
    {
        /** @var CalculateEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $calculateEntity->setTotalWeightResources($this->maxWeight);
            $calculateEntity->setAmount($this->maxWeight / $calculateEntity->getResourceWeight());
            $calculateEntity->setTotalWeightItems($calculateEntity->getAmount() * $calculateEntity->getItemWeight());
        }
    }

    private function calculateWeightProfitQuotient(array $calculateEntityArray): void
    {
        /** @var CalculateEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $calculateEntity->setWeightProfitQuotient(
                $calculateEntity->getPercentageProfit() / $calculateEntity->getTotalWeightResources()
            );
        }
    }

    private function calculateColorGrade(array $calculateEntityArray)
    {
        /** @var CalculateEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $quotient = $calculateEntity->getWeightProfitQuotient();
            $colorGrade = match (true) {
                $quotient >= 1000 => 'S',
                $quotient >= 400 => 'A',
                $quotient >= 100 => 'B',
                $quotient >= 0 => 'C',
                $quotient <= 0 => 'D',
            };
            $calculateEntity->setColorGrade($colorGrade);
        }
    }

    private function calculateAgeOfPrices(array $calculateEntityArray)
    {
        /** @var CalculateEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $now = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s',Date('Y-m-d H:i:s'));

            $itemPriceDate = $calculateEntity->getItemSellOrderPriceDate();
            $itemDiff = date_diff($now, $itemPriceDate);
            $calculateEntity->setItemPriceAge($this->getAgeInMin($itemDiff));

            $primaryPriceDate = $calculateEntity->getPrimarySellOrderPriceDate();
            $primaryDiff = date_diff($now, $primaryPriceDate);
            $calculateEntity->setPrimaryPriceAge($this->getAgeInMin($primaryDiff));


            $secondaryPriceDate = $calculateEntity->getSecondarySellOrderPriceDate();
            $secondaryDiff = date_diff($now, $secondaryPriceDate);
            $calculateEntity->setSecondaryPriceAge($this->getAgeInMin($secondaryDiff));
        }
    }

    private function getAgeInMin(\DateInterval $itemDiff): int
    {
        return $itemDiff->d * 24 * 60 + $itemDiff->h * 60 + $itemDiff->i;
    }
}
