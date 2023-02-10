<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;

class EnchantingHelper extends Market
{
    public function getEnchantment(int $tier): int
    {
        return (int) substr((string) $tier, -1);
    }

    public function calculateHigherEnchantmentItem(int $tier, string $name, array $items): ItemEntity
    {

        /** @var ItemEntity $item */
        foreach ($items as $item) {
            if ($item->getName() === $name && $item->getTier() === ($tier + 1)) {
                return $item;
            }
        }
        throw new \InvalidArgumentException('No Higher Enchantment found in calculateHigherEnchantmentItem');
    }

    public function calculateEnchantmentMaterial(int $tier, ?array $materials): MaterialEntity
    {
        $enchantment = $this->getEnchantment($tier);
        $type = match ($enchantment) {
            0 => 'rune',
            1 => 'soul',
            2 => 'relic'
        };

        /** @var MaterialEntity $material */
        foreach ($materials as $material) {
            if ($material->getName() === $type && $material->getTier() / 10 === (int) ($tier / 10)) {
                return $material;
            }
        }
        throw new \InvalidArgumentException('No Material found in calculateEnchantmentMaterial');
    }

    public function calculateMaterialAmount(int $totalResourceAmount): int
    {
        return match ($totalResourceAmount) {
            8 => 48,
            16 => 96,
            24 => 144,
            32 => 192,
        };
    }

    public function calculateMaterialCost(int $materialAmount, int $buyOrderPrice): float
    {
        return $this->calculateBuyOrder($buyOrderPrice) * $materialAmount;
    }

    public function calculateProfit(int $baseItemPrice, int $enchantedItemPrice, float $materialCost): float
    {
        return $enchantedItemPrice - ($baseItemPrice + $materialCost);
    }

}