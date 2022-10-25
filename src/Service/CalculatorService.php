<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\CalculateEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\ResourceRepository;

class CalculatorService
{
    private const RRR_BONUS_CITY_NO_FOCUS = 24.8;
    private const RRR_BONUS_CITY_FOCUS = 47.9;
    private const RRR_BASE_PERCENTAGE = 100;

    private int $amount;

    public function __construct(
        private ItemRepository $itemRepository,
        private ResourceRepository $resourceRepository,
    ) {
    }

    public function getDataForCity(string $city, int $amount = 1, float $percentage = self::RRR_BONUS_CITY_FOCUS): array
    {
        $this->amount = $amount;
        $items = $this->itemRepository->getItemsFromCity($city);
        $resources = $this->resourceRepository->getResourcesByCity($city);

        $calculateEntityArray = [];
        /** @var ItemEntity $item */
        foreach ($items as $item) {
            $calculateEntityArray[] = new CalculateEntity($item, $resources);
        }

        $this->calculateProfit($calculateEntityArray, $percentage);
        $this->calculateTotalWeight($calculateEntityArray);
        $this->calculateWeightProfitQuotient($calculateEntityArray);
        $this->calculateColorGrade($calculateEntityArray);
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
            $calculateEntity->setNoFocusProfit($this->calculateProfitNoFocus($calculateEntity));
            $calculateEntity->setPercentageProfit($this->calculateProfitByPercentage($calculateEntity, $percentage));
        }
    }

    private function calculateProfitNoFocus(CalculateEntity $calculateEntity): float
    {
        $itemCost = $calculateEntity->getPrimarySellOrderPrice() *
            $calculateEntity->getPrimaryResourceAmount() +
            $calculateEntity->getSecondarySellOrderPrice() *
            $calculateEntity->getSecondaryResourceAmount();
        $rate = (self::RRR_BASE_PERCENTAGE - self::RRR_BONUS_CITY_NO_FOCUS) / 100;
        return ($calculateEntity->getItemSellOrderPrice() - ($itemCost * $rate)) * $this->amount;
    }

    private function calculateProfitByPercentage(CalculateEntity $calculateEntity, float $percentage): float
    {
        $itemCost = $calculateEntity->getPrimarySellOrderPrice() *
            $calculateEntity->getPrimaryResourceAmount() +
            $calculateEntity->getSecondarySellOrderPrice() *
            $calculateEntity->getSecondaryResourceAmount();
        $rate = (self::RRR_BASE_PERCENTAGE - $percentage) / 100;
        return ($calculateEntity->getItemSellOrderPrice() - ($itemCost * $rate)) * $this->amount;
    }

    private function calculateTotalWeight(array $calculateEntityArray): void
    {
        /** @var CalculateEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $calculateEntity->setTotalWeightItems($this->amount * $calculateEntity->getItemWeight());

            $weight = $calculateEntity->getResourceWeight();
            $calculateEntity->setTotalWeightResources($this->amount * $weight);
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
}
