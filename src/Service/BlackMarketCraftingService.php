<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
use MZierdt\Albion\Entity\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;
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

    private const PREMIUM_FACTOR = 1.5;
    public const MARKET_SETUP = 0.025;
    public const MARKET_FEE = 0.04;
    private const NUTRITION_FACTOR = 0.1125;

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
            $this->calculateResource($bmcEntity, $resources);
            $this->calculateAmountOfJournals($bmcEntity, $journals);
            $this->calculateTotalAmount($bmcEntity, $weight);
            $this->calculateCraftingFee($bmcEntity, $feeProHundredNutrition);
            $this->calculateProfit($bmcEntity, $percentage, $order);
            $this->calculateWeightProfitQuotient($bmcEntity);
        }

        dd($calculateEntityArray);
//        return $this->filterCalculateEntityArray($calculateEntityArray);
        return  $calculateEntityArray;
    }


    private function filterCalculateEntityArray(array $calculateEntityArray): array
    {
        $array = [];
        /** @var BlackMarketCraftingEntity $calculateEntity */
        foreach ($calculateEntityArray as $calculateEntity) {
            $array[$calculateEntity->getWeaponGroup() . '_' . $calculateEntity->getRealName()][] = $calculateEntity;
        }
        krsort($array);
        return $array;
    }

    private function calculateProfit(
        BlackMarketCraftingEntity $bmcEntity,
        float $percentage,
        string $order,
    ): void {
        $profitBooks = $this->getProfitBooks($bmcEntity);
        $profit = $this->calculateProfitByPercentage($bmcEntity, $percentage, $order);
        $craftingFee = $bmcEntity->getCraftingFee();

        $bmcEntity->setProfitBooks($profitBooks);
        $bmcEntity->setProfit($profit - $craftingFee + $profitBooks);
    }

    private function calculateProfitByPercentage(
        BlackMarketCraftingEntity $bmcEntity,
        float $percentage,
        string $order,
    ): float {
        if ($order === '1') {
            $itemCost = $bmcEntity->getPrimResource()->getSellOrderPrice() *
                $bmcEntity->getItem()->getPrimaryResourceAmount() +
                $bmcEntity->getSecResource()->getSellOrderPrice() *
                $bmcEntity->getItem()->getSecondaryResourceAmount();
        } else {
            $itemCost = ($bmcEntity->getPrimResource()->getBuyOrderPrice() *
                    $bmcEntity->getItem()->getPrimaryResourceAmount() +
                    $bmcEntity->getSecResource()->getBuyOrderPrice() *
                    $bmcEntity->getItem()->getSecondaryResourceAmount()) *
                (1 + self::MARKET_SETUP);
        }

        $rate = (self::RRR_BASE_PERCENTAGE - $percentage) / 100;
        $amount = $bmcEntity->getTotalAmount();
        $itemSellPrice = $bmcEntity->getItem()->getSellOrderPrice() * (1 - self::MARKET_SETUP - self::MARKET_FEE);
        return ($itemSellPrice - ($itemCost * $rate)) * $amount;
    }

    public function getProfitBooks(BlackMarketCraftingEntity $bmcEntity): float
    {
        return (($bmcEntity->getJournalEntityFull()->getSellOrderPrice() *
                    (1 - self::MARKET_FEE - self::MARKET_SETUP)) -
                $bmcEntity->getJournalEntityEmpty()->getSellOrderPrice()) *
            $bmcEntity->getJournalAmount();
    }

    private function calculateWeightProfitQuotient(BlackMarketCraftingEntity $bmcEntity): void
    {
            $bmcEntity->setWeightProfitQuotient($bmcEntity->getWeight() / $bmcEntity->getProfit());

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

    public function getCraftingRates(): array
    {
        return [
            'No City Bonus No Focus' => self::RRR_NO_BONUS_CITY_NO_FOCUS,
            'No City Bonus Focus' => self::RRR_NO_BONUS_CITY_FOCUS,
            'City Bonus No Focus' => self::RRR_BONUS_CITY_NO_FOCUS,
            'City Bonus Focus' => self::RRR_BONUS_CITY_FOCUS,
        ];
    }

    private function calculateCraftingFee(BlackMarketCraftingEntity $bmcEntity, int $feeProHundredNutrition): void {
        $nutrition = $bmcEntity->getItem()->getItemValue() * self::NUTRITION_FACTOR;
        $bmcEntity->setCraftingFee($nutrition * $feeProHundredNutrition / 100);
    }

    private function calculateResource(BlackMarketCraftingEntity $bmcEntity, array $resources): void
    {
        $primResource = $bmcEntity->getItem()->getPrimaryResource();
        $secResource = $bmcEntity->getItem()->getSecondaryResource();
        $tier = $bmcEntity->getItem()->getTier();

        /** @var ResourceEntity $resource */
        foreach ($resources as $resource) {
            if ($tier === $resource->getTier()) {
                if ($resource->getRealName() === $primResource) {
                    $bmcEntity->setPrimResource($resource);
                }
                if ($resource->getRealName() === $secResource) {
                    $bmcEntity->setSecResource(($resource));
                }
            }
        }
    }

    private function calculateAmountOfJournals(BlackMarketCraftingEntity $bmcEntity, array $journals): void
    {
        $fameFactor = $bmcEntity->getItem()->getFameFactor();
        $tier = $bmcEntity->getItem()->getTier();
        $primAmount = $bmcEntity->getItem()->getPrimaryResourceAmount();
        $secAmount = $bmcEntity->getItem()->getSecondaryResourceAmount();

        $totalAmount = $primAmount + $secAmount;
        $mainTier = $tier[0];
        /** @var JournalEntity $journal */
        foreach ($journals as $journal) {
            if ($mainTier === $journal->getTier()) {
                if ($journal->getFillStatus() === 'empty') {
                    $journalAmount = ($fameFactor * $totalAmount) / $journal->getFameToFill();
                    $bmcEntity->setJournalAmountPerItem($journalAmount);
                    $bmcEntity->setJournalEntityEmpty($journal);
                }
                if ($journal->getFillStatus() === 'full') {
                    $bmcEntity->setJournalEntityFull($journal);
                }
            }
        }
    }

    private function calculateTotalAmount(BlackMarketCraftingEntity $bmcEntity, int $weight): void
    {
        $resourceWeightForItem = $bmcEntity->getPrimResource()->getWeight() *
            ($bmcEntity->getItem()->getPrimaryResourceAmount() +
                $bmcEntity->getItem()->getSecondaryResourceAmount());
        $journalWeightForItem = $bmcEntity->getJournalEntityEmpty()->getWeight() *
            $bmcEntity->getJournalAmountPerItem();

        $totalAmount = (int)($weight / ($resourceWeightForItem + $journalWeightForItem));

        $bmcEntity->setTotalAmount($totalAmount);
        $bmcEntity->setPrimResourceAmount($totalAmount * $bmcEntity->getItem()->getPrimaryResourceAmount());
        $bmcEntity->setSecResourceAmount($totalAmount * $bmcEntity->getItem()->getSecondaryResourceAmount());
        $bmcEntity->setJournalAmount((int)ceil($totalAmount * $bmcEntity->getJournalEntityEmpty()->getWeight()));
        $bmcEntity->setTotalItemWeight($totalAmount * $bmcEntity->getItem()->getWeight());
    }
}
