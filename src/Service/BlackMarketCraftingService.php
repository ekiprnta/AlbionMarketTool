<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
use MZierdt\Albion\Entity\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\JournalRepository;
use MZierdt\Albion\repositories\ResourceRepository;

class BlackMarketCraftingService
{
    private const RRR_BONUS_CITY_NO_FOCUS = 24.8;
    private const RRR_BONUS_CITY_FOCUS = 47.9;
    private const RRR_NO_BONUS_CITY_NO_FOCUS = 15.2;
    private const RRR_NO_BONUS_CITY_FOCUS = 43.5;

    private const PREMIUM_FACTOR = 1.5;

    public function __construct(
        private ItemRepository $itemRepository,
        private ResourceRepository $resourceRepository,
        private JournalRepository $journalRepository,
    ) {
    }

    public function getDataForCity(
        string $itemCity,
        int $weight,
        float $percentage,
        int $feeProHundredNutrition,
        string $resourceCity,
        string $order
    ): array {
        if (empty($itemCity)) {
            throw new InvalidArgumentException('Please select a city');
        }
        if (empty($weight)) {
            throw new InvalidArgumentException('Please insert your maximum carry Weight');
        }
        if (empty($percentage)) {
            $percentage = self::RRR_BONUS_CITY_NO_FOCUS;
        }
        if (empty($feeProHundredNutrition)) {
            $feeProHundredNutrition = 0;
        }
        if (empty($resourceCity)) {
            $resourceCity = $itemCity;
        }
        $items = $this->itemRepository->getBlackMarketItemsFromCity($itemCity);
        $resources = $this->resourceRepository->getResourcesByCity($resourceCity);
        $journals = $this->journalRepository->getJournalsFromCity($resourceCity);

        $calculateEntityArray = [];
        /** @var ItemEntity $item */
        foreach ($items as $item) {
            $calculateEntityArray[] = new BlackMarketCraftingEntity($item, $weight);
        }
        /** @var BlackMarketCraftingEntity $bmcEntity */
        foreach ($calculateEntityArray as $bmcEntity) {
            $bmcEntity->setResources(BlackMarketCraftingHelper::calculateResources($bmcEntity, $resources));
            $bmcEntity->setJournals(BlackMarketCraftingHelper::calculateJournals($bmcEntity, $journals));
            $bmcEntity->setAmounts(BlackMarketCraftingHelper::calculateTotalAmount($bmcEntity, $weight));
            $bmcEntity->setCraftingFee(BlackMarketCraftingHelper::calculateCraftingFee($bmcEntity, $feeProHundredNutrition));
            $bmcEntity->setProfitBooks(BlackMarketCraftingHelper::calculateProfitBooks($bmcEntity));
            $bmcEntity->setProfit(BlackMarketCraftingHelper::calculateProfit($bmcEntity, $percentage, $order));
            $bmcEntity->setWeightProfitQuotient(BlackMarketCraftingHelper::calculateWeightProfitQuotient($bmcEntity));
            $bmcEntity->setColorGrade(BlackMarketCraftingHelper::calculateCraftingGrade($bmcEntity));
        }

        return $this->filterCalculateEntityArray($calculateEntityArray);
//        return  $calculateEntityArray;
    }


    private function filterCalculateEntityArray(array $calculateEntityArray): array
    {
        $array = [];
        /** @var BlackMarketCraftingEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $array[$calculateEntity->getItem()->getWeaponGroup() . '_' . $calculateEntity->getItem()->getRealName()][] = $calculateEntity;
        }
        krsort($array);
        return $array;
    }

    public function getCraftingRates(): array
    {
        return [
            'No City Bonus No Focus' => self::RRR_NO_BONUS_CITY_NO_FOCUS,
            'No City Bonus Focus' => self::RRR_NO_BONUS_CITY_FOCUS,
            'City Bonus No Focus' => self::RRR_BONUS_CITY_NO_FOCUS,
            'City Bonus Focus' => self::RRR_BONUS_CITY_FOCUS,
        ];
    }

}
