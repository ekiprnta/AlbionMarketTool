<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntities\EnchantingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;

class EnchantingService extends Market
{
    public function calculateHigherEnchantmentItem(int $tier, string $name, array $items): ItemEntity
    {
        /** @var ItemEntity $item */
        foreach ($items as $item) {
            if ($item->getName() === $name && $item->getTier() === ($tier + 1)) {
                return $item;
            }
        }
        throw new \InvalidArgumentException(
            'No Higher Enchantment found in calculateHigherEnchantmentItem ' . $tier . ':' . $name
        );
    }

    public function calculateEnchantmentMaterial(int $tier, ?array $materials): MaterialEntity
    {
        $enchantment = $this->getEnchantment($tier);
        $type = match ($enchantment) {
            0 => 'rune',
            1 => 'soul',
            2 => 'relic',
            default => throw new \InvalidArgumentException('Cannot find Material in calculateEnchantmentMaterial')
        };

        /** @var MaterialEntity $material */
        foreach ($materials as $material) {
            if ($material->getName() === $type && $material->getTier() / 10 === (int) ($tier / 10)) {
                return $material;
            }
        }
        throw new \InvalidArgumentException('No Material found in calculateEnchantmentMaterial');
    }

    public function getEnchantment(int $tier): int
    {
        return (int) substr((string) $tier, -1);
    }

    public function calculateMaterialAmount(int $totalResourceAmount): int
    {
        return match ($totalResourceAmount) {
            8 => 48,
            16 => 96,
            24 => 144,
            32 => 192,
            default => throw new \InvalidArgumentException(
                'Wrong Total Resource Amount in calculateMaterialAmount ' . $totalResourceAmount
            )
        };
    }

    public function calculateTotalMaterialCost(float $materialPrice, int $materialAmount): float
    {
        return $materialPrice * $materialAmount;
    }

    public function filterItems(array $items): array // TOdo better filtering
    {
        /** @var ItemEntity $item */
        foreach ($items as $key => $item) {
            if ($item->getTotalResourceAmount() === 8) {
                $bla = 1;
            } elseif ($item->getTotalResourceAmount() === 16) {
                $bla = 1;
            } elseif ($item->getTotalResourceAmount() === 24) {
                $bla = 1;
            } elseif ($item->getTotalResourceAmount() === 32) {
                $bla = 1;
            } else {
                unset($items[$key]);
            }
        }
        return $items;
    }

    public function calculateEnchantingEntity(
        EnchantingEntity $enchantingEntity,
        array $bmItems,
        array $materials,
        array $bmSellsConfig,
        string $city
    ): EnchantingEntity {
        $baseItem = $enchantingEntity->getBaseItem();

        $enchantingEntity->setBaseEnchantment($this->getEnchantment($baseItem->getTier()));

        $enchantingEntity->setHigherEnchantmentItem(
            $this->calculateHigherEnchantmentItem($baseItem->getTier(), $baseItem->getName(), $bmItems)
        );

        $enchantingEntity->setEnchantmentMaterial(
            $this->calculateEnchantmentMaterial($baseItem->getTier(), $materials)
        );

        $enchantingEntity->setMaterialAmount($this->calculateMaterialAmount($baseItem->getTotalResourceAmount()));

        $enchantmentMaterial = $enchantingEntity->getEnchantmentMaterial();
        $enchantingEntity->setMaterialCostBuy(
            $this->calculateTotalMaterialCost(
                $this->calculateBuyOrder($enchantmentMaterial->getBuyOrderPrice()),
                $enchantingEntity->getMaterialAmount(),
            )
        );
        $higherEnchantmentItem = $enchantingEntity->getHigherEnchantmentItem();
        $enchantingEntity->setProfitSell(
            $this->calculateProfit(
                $higherEnchantmentItem->getSellOrderPrice(),
                $baseItem->getSellOrderPrice() + $enchantingEntity->getMaterialCostBuy()
            )
        );
        $enchantingEntity->setProfitPercentageSell(
            $this->calculateProfitPercentage(
                $higherEnchantmentItem
                    ->getSellOrderPrice(),
                $baseItem->getSellOrderPrice() + $enchantingEntity->getMaterialCostBuy()
            )
        );
        $enchantingEntity->setProfitGradeSell(
            $this->calculateProfitGrade($enchantingEntity->getProfitPercentageSell())
        );

        $enchantingEntity->setProfitBuy(
            $this->calculateProfit(
                $higherEnchantmentItem->getSellOrderPrice(),
                $this->calculateBuyOrder($baseItem->getBuyOrderPrice()) + $enchantingEntity->getMaterialCostBuy()
            )
        );
        $enchantingEntity->setProfitPercentageBuy(
            $this->calculateProfitPercentage(
                $higherEnchantmentItem->getSellOrderPrice(),
                $this->calculateBuyOrder($baseItem->getBuyOrderPrice()) + $enchantingEntity->getMaterialCostBuy()
            )
        );
        $enchantingEntity->setProfitGradeBuy(
            $this->calculateProfitGrade($enchantingEntity->getProfitPercentageBuy())
        );

        $enchantingEntity->setComplete(
            $this->isComplete([
                $higherEnchantmentItem->getSellOrderPrice(),
                $baseItem->getSellOrderPrice(),
                $baseItem->getBuyOrderPrice(),
                $enchantmentMaterial->getBuyOrderPrice(),
            ])
        );
        $enchantingEntity->setCity($city);
        $enchantingEntity->setAmount(
            $this->calculateAmount(
                $higherEnchantmentItem->getTier(),
                $higherEnchantmentItem->getTotalResourceAmount(),
                $bmSellsConfig
            )
        );

        return $enchantingEntity;
    }

    public function calculateAmount(int $tier, int $resourceAmount, array $bmSellsConfig): int
    {
        return $bmSellsConfig[$tier][$resourceAmount];
    }
}
