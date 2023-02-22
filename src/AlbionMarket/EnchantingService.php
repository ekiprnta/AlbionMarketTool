<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionMarket;

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

    public function calculateTotalMaterialCost(int $buyOrderPrice, int $materialAmount): float
    {
        return $this->calculateBuyOrder($buyOrderPrice) * $materialAmount;
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
}
