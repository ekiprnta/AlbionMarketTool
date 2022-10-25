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
        $filteredArray = $this->filterCalculateEntityArray($calculateEntityArray);
        dd($filteredArray);
        die();
    }

    private function filterCalculateEntityArray(array $calculateEntityArray)
    {
        $array = [];
        /** @var CalculateEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $array[$calculateEntity->getWeaponGroup()][] = $calculateEntity;
        }
        arsort($array);
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
        $itemCost = $calculateEntity->getPrimarySellOrderPrice() + $calculateEntity->getSecondarySellOrderPrice();
        $rate = (self::RRR_BASE_PERCENTAGE - self::RRR_BONUS_CITY_NO_FOCUS);
        return ($calculateEntity->getItemSellOrderPrice() - $itemCost) * $rate * $this->amount;
    }

    private function calculateProfitByPercentage(CalculateEntity $calculateEntity, float $percentage): float
    {
        $itemCost = $calculateEntity->getPrimarySellOrderPrice() + $calculateEntity->getSecondarySellOrderPrice();
        $rate = (self::RRR_BASE_PERCENTAGE - $percentage);
        return ($calculateEntity->getItemSellOrderPrice() - $itemCost) * $rate * $this->amount;
    }

}
