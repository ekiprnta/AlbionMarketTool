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

    public function __construct(
        private ItemRepository $itemRepository,
        private ResourceRepository $resourceRepository,
    ) {
    }

    public function getDataForCity(string $city, float $percentage): array
    {
        $items = $this->itemRepository->getItemsFromCity($city);
        $resources = $this->resourceRepository->getResourcesByCity($city);

        $calculateEntityArray = [];
        /** @var ItemEntity $item */
        foreach ($items as $item) {
            $calculateEntityArray[] = new CalculateEntity($item, $resources);
        }

        $calculate = $this->calculateProfit($calculateEntityArray);
        $filteredArray = $this->filterCalculateEntityArray($calculate);

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

    private function calculateProfit(array $calculateEntityArray, string $percentage)
    {
        /** @var CalculateEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $this->calculateProfitNoFocus($calculateEntity);
            $this->calculateProfitByPercentage($calculateEntity, $percentage);
        }
    }

}
