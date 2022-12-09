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


    public function __construct(
        private ItemRepository $itemRepository,
        private ResourceRepository $resourceRepository,
        private JournalRepository $journalRepository,
        private BlackMarketCraftingHelper $bmtHelper
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
            $bmcEntity->setPrimResource(
                $this->bmtHelper->calculateResource(
                    $bmcEntity->getItem()
                        ->getPrimaryResource(),
                    $bmcEntity->getItem()
                        ->getTier(),
                    $resources
                )
            );
            $bmcEntity->setSecResource(
                $this->bmtHelper->calculateResource(
                    $bmcEntity->getItem()
                        ->getSecondaryResource(),
                    $bmcEntity->getItem()
                        ->getTier(),
                    $resources
                )
            );
            $bmcEntity->setJournalEntityFull(
                $this->bmtHelper->calculateJournal($bmcEntity->getItem()->getTier(), 'full', $journals)
            );
            $bmcEntity->setJournalEntityEmpty(
                $this->bmtHelper->calculateJournal($bmcEntity->getItem()->getTier(), 'empty', $journals)
            );
            $bmcEntity->setJournalAmountPerItem(
                $this->bmtHelper->calculateJournalAmountPerItem(
                    $bmcEntity->getItem()
                        ->getFame(),
                    $bmcEntity->getJournalEntityEmpty()
                        ->getFameToFill()
                )
            );

            $bmcEntity->setTotalAmount(
                $this->bmtHelper->calculateTotalAmount(
                    $bmcEntity->getPrimResource()
                        ->getWeight(),
                    $bmcEntity->getItem()
                        ->getPrimaryResourceAmount() +
                    $bmcEntity->getItem()
                        ->getSecondaryResourceAmount(),
                    $bmcEntity->getJournalEntityEmpty()
                        ->getWeight(),
                    $bmcEntity->getJournalAmountPerItem(),
                    $weight
                )
            );
            $bmcEntity->setPrimResourceAmount(
                $this->bmtHelper->calculateResourceAmount(
                    $bmcEntity->getTotalAmount(),
                    $bmcEntity->getItem()
                        ->getPrimaryResourceAmount()
                )
            );
            $bmcEntity->setSecResourceAmount(
                $this->bmtHelper->calculateResourceAmount(
                    $bmcEntity->getTotalAmount(),
                    $bmcEntity->getItem()
                        ->getSecondaryResourceAmount()
                )
            );
            $bmcEntity->setJournalAmount(
                $this->bmtHelper->calculateJournalAmount(
                    $bmcEntity->getTotalAmount(),
                    $bmcEntity->getJournalAmountPerItem()
                )
            );
            $bmcEntity->setTotalItemWeight(
                $this->bmtHelper->calculateTotalItemWeight(
                    $bmcEntity->getTotalAmount(),
                    $bmcEntity->getItem()
                        ->getWeight()
                )
            );

            $bmcEntity->setFameAmount(
                $this->bmtHelper->calculateFameAmount($bmcEntity->getTotalAmount(), $bmcEntity->getItem()->getFame())
            );
            $bmcEntity->setCraftingFee(
                $this->bmtHelper->calculateCraftingFee($bmcEntity->getItem()->getItemValue(), $feeProHundredNutrition)
            );
            $bmcEntity->setProfitJournals(
                $this->bmtHelper->calculateProfitJournals(
                    $bmcEntity->getJournalEntityEmpty()
                        ->getBuyOrderPrice(),
                    $bmcEntity->getJournalEntityFull()
                        ->getSellOrderPrice(),
                    $bmcEntity->getJournalAmount()
                )
            );

            $itemCost = $this->calculateItemCost($bmcEntity, $order);
            $bmcEntity->setProfit(
                $this->bmtHelper->calculateProfit(
                    $bmcEntity->getTotalAmount(),
                    $bmcEntity->getItem()
                        ->getSellOrderPrice(),
                    $itemCost,
                    $percentage,
                    $bmcEntity->getCraftingFee(),
                    $bmcEntity->getProfitJournals(),
                )
            );
            $bmcEntity->setItemValue(
                $this->bmtHelper->calculateItemValue(
                    $bmcEntity->getTotalAmount(),
                    $bmcEntity->getItem()
                        ->getSellOrderPrice()
                )
            );
            $bmcEntity->setWeightProfitQuotient(
                $this->bmtHelper->calculateWeightProfitQuotient(
                    $bmcEntity->getProfit(),
                    $bmcEntity->getTotalWeightResources()
                )
            );
            $bmcEntity->setColorGrade(
                $this->bmtHelper->calculateProfitGrade($bmcEntity->getWeightProfitQuotient())
            );
        }

        return $this->filterCalculateEntityArray($calculateEntityArray);
//        return  $calculateEntityArray;
    }


    private function filterCalculateEntityArray(array $calculateEntityArray): array
    {
        $array = [];
        /** @var BlackMarketCraftingEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $array[$calculateEntity->getItem()->getWeaponGroup() . '_' . $calculateEntity->getItem()->getRealName(
            )][] = $calculateEntity;
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

    private function calculateItemCost(BlackMarketCraftingEntity $bmcEntity, string $order): float
    {
        if ($order === '1') {
            $itemCost = $this->bmtHelper->calculateSellOrderItemCost(
                $bmcEntity->getPrimResource()
                    ->getSellOrderPrice(),
                $bmcEntity->getItem()
                    ->getPrimaryResourceAmount(),
                $bmcEntity->getSecResource()
                    ->getSellOrderPrice(),
                $bmcEntity->getItem()
                    ->getSecondaryResourceAmount()
            );
        } else {
            $itemCost = $this->bmtHelper->calculateBuyOrderItemCost(
                $bmcEntity->getPrimResource()
                    ->getBuyOrderPrice(),
                $bmcEntity->getItem()
                    ->getPrimaryResourceAmount(),
                $bmcEntity->getSecResource()
                    ->getBuyOrderPrice(),
                $bmcEntity->getItem()
                    ->getSecondaryResourceAmount()
            );
        }
        return $itemCost;
    }
}
