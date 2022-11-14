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
    private const RRR_BASE_PERCENTAGE = 100;

    private int $maxWeight;

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
        if (empty($resourceCity)) {
            $resourceCity = $itemCity;
        }
        $this->maxWeight = $weight;
        $items = $this->itemRepository->getBlackMarketItemsFromCity($itemCity);
        $resources = $this->resourceRepository->getResourcesByCity($resourceCity);
        $journals = $this->journalRepository->getJournalsFromCity($resourceCity);

        $calculateEntityArray = [];
        /** @var ItemEntity $item */
        foreach ($items as $item) {
            $calculateEntityArray[] = new BlackMarketCraftingEntity($item, $resources, $journals);
        }
        $this->calculateTotalWeight($calculateEntityArray);
        $this->calculateTotalAmountResources($calculateEntityArray);
        $this->calculateProfit($calculateEntityArray, $percentage, $order);
        $this->calculateWeightProfitQuotient($calculateEntityArray);
        $this->calculateColorGrade($calculateEntityArray);
        $this->calculateAgeOfPrices($calculateEntityArray, $order);
        $filteredArray = $this->filterCalculateEntityArray($calculateEntityArray);
//        dd($filteredArray);
        return $filteredArray;
    }

    private function filterCalculateEntityArray(array $calculateEntityArray)
    {
        $array = [];
        /** @var BlackMarketCraftingEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $array[$calculateEntity->getWeaponGroup() . '_' . $calculateEntity->getRealName()][] = $calculateEntity;
        }
        krsort($array);
        return $array;
    }

    private function calculateProfit(array $calculateEntityArray, float $percentage, string $order): void
    {
        /** @var BlackMarketCraftingEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $profit = $this->calculateProfitByPercentage($calculateEntity, $percentage, $order);
            $calculateEntity->setPercentageProfit($profit);
        }
    }

    private function calculateProfitByPercentage(
        BlackMarketCraftingEntity $calculateEntity,
        float $percentage,
        string $order
    ): float {
        if ($order === '1') {
            $itemCost = $calculateEntity->getPrimarySellOrderPrice() *
                $calculateEntity->getPrimaryResourceAmount() +
                $calculateEntity->getSecondarySellOrderPrice() *
                $calculateEntity->getSecondaryResourceAmount();
        } else {
            $itemCost = $calculateEntity->getPrimaryBuyOrderPrice() *
                $calculateEntity->getPrimaryResourceAmount() +
                $calculateEntity->getSecondaryBuyOrderPrice() *
                $calculateEntity->getSecondaryResourceAmount();
        }

        $rate = (self::RRR_BASE_PERCENTAGE - $percentage) / 100;
        $amount = $calculateEntity->getAmount();
        $profitBooks = $this->calculateProfitBooks($calculateEntity);
        $itemSellPrice = $calculateEntity->getItemSellOrderPrice();
        return ($itemSellPrice - ($itemCost * $rate)) * $amount + $profitBooks;
    }

    private function calculateTotalWeight(array $calculateEntityArray): void
    {
        /** @var BlackMarketCraftingEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $calculateEntity->setTotalWeightResources($this->maxWeight);
            $amount = $this->maxWeight / ($calculateEntity->getResourceWeight() + $calculateEntity->getJournalWeight());
            $calculateEntity->setAmount($amount);
            $calculateEntity->setAmountBooks($amount);
            $calculateEntity->setTotalWeightItems($calculateEntity->getAmount() * $calculateEntity->getItemWeight());
        }
    }

    private function calculateWeightProfitQuotient(array $calculateEntityArray): void
    {
        /** @var BlackMarketCraftingEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $calculateEntity->setWeightProfitQuotient(
                $calculateEntity->getPercentageProfit() / $calculateEntity->getTotalWeightResources()
            );
        }
    }

    private function calculateColorGrade(array $calculateEntityArray): void
    {
        /** @var BlackMarketCraftingEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $quotient = $calculateEntity->getWeightProfitQuotient();
            $colorGrade = match (true) {
                $quotient >= 1000 => 'S',
                $quotient >= 400 => 'A',
                $quotient >= 100 => 'B',
                $quotient >= 0 => 'C',
                default => 'D',
            };
            $calculateEntity->setColorGrade($colorGrade);
        }
    }

    private function calculateAgeOfPrices(array $calculateEntityArray, string $order): void
    {
        /** @var BlackMarketCraftingEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $now = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', Date('Y-m-d H:i:s'));

            $itemPriceDate = $calculateEntity->getItemSellOrderPriceDate();
            $itemDiff = date_diff($now, $itemPriceDate);
            $calculateEntity->setItemPriceAge($this->getAgeInMin($itemDiff));

            if ($order === '1') {
                $primaryPriceDate = $calculateEntity->getPrimarySellOrderPriceDate();
            } else {
                $primaryPriceDate = $calculateEntity->getPrimaryBuyOrderPriceDate();
            }
            $primaryDiff = date_diff($now, $primaryPriceDate);
            $calculateEntity->setPrimaryPriceAge($this->getAgeInMin($primaryDiff));

            if ($calculateEntity->getSecondarySellOrderPriceDate() !== null) {
                if ($order === '1') {
                    $secondaryPriceDate = $calculateEntity->getSecondarySellOrderPriceDate();
                } else {
                    $secondaryPriceDate = $calculateEntity->getSecondaryBuyOrderPriceDate();
                }
                $secondaryDiff = date_diff($now, $secondaryPriceDate);
                $calculateEntity->setSecondaryPriceAge($this->getAgeInMin($secondaryDiff));
            }
        }
    }

    private function getAgeInMin(\DateInterval $itemDiff): int
    {
        return $itemDiff->d * 24 * 60 + $itemDiff->h * 60 + $itemDiff->i;
    }

    private function calculateProfitBooks(BlackMarketCraftingEntity $calculateEntity): int
    {
        return ($calculateEntity->getFullSellOrderPrice() -
                $calculateEntity->getEmptySellOrderPrice()) *
            $calculateEntity->getAmountBooks();
    }

    private function calculateTotalAmountResources(array $calculateEntityArray): void
    {
        /** @var BlackMarketCraftingEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $calculateEntity->setPrimaryTotalAmount(
                $calculateEntity->getPrimaryResourceAmount() *
                $calculateEntity->getAmount()
            );
            $calculateEntity->setSecondaryTotalAmount(
                $calculateEntity->getSecondaryResourceAmount() *
                $calculateEntity->getAmount()
            );
        }
    }

    public function getPrimResource(string $itemName)
    {
        return NameDataService::getPrimResource($itemName);
    }

    public function getSecResource(string $itemName)
    {
        return NameDataService::getSecResource($itemName);
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
