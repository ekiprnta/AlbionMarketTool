<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\AlbionItemEntity;
use MZierdt\Albion\Entity\ItemEntity;

class BlackMarketTransportingService extends Market
{
    public function __construct()
    {
    }

    public function calculateCityItem(ItemEntity $bmItem, array $Items): ItemEntity
    {
        /** @var ItemEntity $item */
        foreach ($Items as $item) {
            if ($item->getTier() === $bmItem->getTier() &&
                $item->getName() === $bmItem->getName()) {
                return $item;
            }
        }
        throw new \RuntimeException('No Item found for ' . $bmItem->getName());
    }

    public function calculateAmount(int $primAmount, int $secAmount, array $amountConfig): int
    {
        $totalAmount = $primAmount + $secAmount;
        return $amountConfig[$totalAmount];
    }

    public function calculateTierString(int $tier): string
    {
        return match ($tier) {
            AlbionItemEntity::TIER_T2 => 'twenty',
            AlbionItemEntity::TIER_T3 => 'thirty',
            AlbionItemEntity::TIER_T4 => 'forty',
            AlbionItemEntity::TIER_T4_1 => 'fortyOne',
            AlbionItemEntity::TIER_T4_2 => 'fortyTwo',
            AlbionItemEntity::TIER_T4_3 => 'fortyThree',
            AlbionItemEntity::TIER_T4_4 => 'fortyFour',
            AlbionItemEntity::TIER_T5 => 'fifty',
            AlbionItemEntity::TIER_T5_1 => 'fiftyOne',
            AlbionItemEntity::TIER_T5_2 => 'fiftyTwo',
            AlbionItemEntity::TIER_T5_3 => 'fiftyThree',
            AlbionItemEntity::TIER_T5_4 => 'fiftyFour',
            AlbionItemEntity::TIER_T6 => 'sixty',
            AlbionItemEntity::TIER_T6_1 => 'sixtyOne',
            AlbionItemEntity::TIER_T6_2 => 'sixtyTwo',
            AlbionItemEntity::TIER_T6_3 => 'sixtyThree',
            AlbionItemEntity::TIER_T6_4 => 'sixtyFour',
            AlbionItemEntity::TIER_T7 => 'seventy',
            AlbionItemEntity::TIER_T7_1 => 'seventyOne',
            AlbionItemEntity::TIER_T7_2 => 'seventyTwo',
            AlbionItemEntity::TIER_T7_3 => 'seventyThree',
            AlbionItemEntity::TIER_T7_4 => 'seventyFour',
            AlbionItemEntity::TIER_T8 => 'eighty',
            AlbionItemEntity::TIER_T8_1 => 'eightyOne',
            AlbionItemEntity::TIER_T8_2 => 'eightyTwo',
            AlbionItemEntity::TIER_T8_3 => 'eightyThree',
            AlbionItemEntity::TIER_T8_4 => 'eightyFour',
            default => throw new \InvalidArgumentException('Cannot find correct Tier in calculateTierString ' . $tier)
        };
    }

    public function calculateBmtEntity(
        BlackMarketTransportEntity $bmtEntity,
        array $cityItems,
        $amountConfig1,
        string $city
    ): BlackMarketTransportEntity {
        $bmItem = $bmtEntity->getBmItem();
        $bmtEntity->setCityItem($this->calculateCityItem($bmItem, $cityItems));
        $cityItem = $bmtEntity->getCityItem();
        $bmtEntity->setAmount(
            $this->calculateAmount(
                $cityItem
                    ->getPrimaryResourceAmount(),
                $cityItem
                    ->getSecondaryResourceAmount(),
                $amountConfig1
            )
        );
        $bmtEntity->setMaterialCostSell($cityItem->getSellOrderPrice());
        $bmtEntity->setProfitSell(
            $this->calculateProfit(
                $bmItem->getSellOrderPrice(),
                (int) $bmtEntity->getMaterialCostSell()
            )
        );
        $bmtEntity->setProfitPercentageSell(
            $this->calculateProfitPercentage(
                $bmItem->getSellOrderPrice(),
                $cityItem->getSellOrderPrice()
            )
        );
        $bmtEntity->setProfitGradeSell(
            $this->calculateProfitGrade($bmtEntity->getProfitPercentageSell())
        );

        $cityItemPrice = $cityItem->getBuyOrderPrice();
        $bmtEntity->setMaterialCostBuy($this->calculateBuyOrder($cityItemPrice));
        $bmtEntity->setProfitBuy(
            $this->calculateProfit($bmItem->getBuyOrderPrice(), (int) $bmtEntity->getMaterialCostBuy())
        );
        $bmtEntity->setProfitPercentageBuy(
            $this->calculateProfitPercentage($bmItem->getBuyOrderPrice(), $cityItemPrice)
        );
        $bmtEntity->setProfitGradeBuy(
            $this->calculateProfitGrade($bmtEntity->getProfitPercentageBuy())
        );

        $bmtEntity->setComplete(
            $this->isComplete(
                [$bmItem->getSellOrderPrice(), $cityItem->getSellOrderPrice(), $cityItem->getBuyOrderPrice()]
            )
        );
        $bmtEntity->setCity($city);
        $bmtEntity->setTierString($this->calculateTierString($bmtEntity->getBmItem()->getTier()));

        return $bmtEntity;
    }
}
