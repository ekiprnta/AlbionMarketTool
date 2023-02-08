<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ResourceEntity;

class RefiningHelper extends Market
{
    public function calculateAmountRawResource(int $tier): int
    {
        $baseTier = (int) ($tier / 10);
        return match ($baseTier) {
            3, 4 => 2,
            5 => 3,
            6 => 4,
            7, 8 => 5,
            default => 0,
        };
    }

    public function calculateResource(int $tier, array $rawResources): ResourceEntity
    {
        /** @var ResourceEntity $rawResource */
        foreach ($rawResources as $rawResource) {
            if ($rawResource->getTier() === $tier) {
                return $rawResource;
            }
        }
        throw new \InvalidArgumentException('No Resource found for Tier: ' . $tier . ' in RefiningHelper.php');
    }

    public function calculateLowerResourceTier(int $tier): int
    {
        $baseTier = (int) ($tier / 10);
        if ($baseTier === 4) {
            return 30;
        }
        return $tier - 10;
    }

    public function calculateProfit(
        int $refinedResourcePrice,
        int $rawResourcePrice,
        int $lowerResourcePrice,
        int $amountRawResource,
        float $percentage
    ): float {
        $rate = (self::RRR_BASE_PERCENTAGE - $percentage) / 100;
        $itemCost = $amountRawResource * $rawResourcePrice + $lowerResourcePrice;
        return $this->calculateSellOrder($refinedResourcePrice) - $itemCost * $rate;
    }

    public function calculateRefiningAmount(int $tier): int
    {
        return match ($tier) {
            30 => 968,
            40 => 10000,
            41, 50 => 5000,
            42 => 3333,
            43 => 2000,
            44 => 968,
            51, 60 => 3000,
            52 => 1765,
            53 => 1035,
            54 => 566,
            61, 70 => 1667,
            62 => 1000,
            63 => 566,
            64 => 319,
            71, 80 => 968,
            72 => 556,
            73 => 319,
            74 => 180,
            81 => 545,
            82 => 316,
            83 => 180,
            84 => 101,
            default => throw new \InvalidArgumentException('Wrong tier for Refining Amount: ' . $tier . '(Amount)'),
        };
    }

    public function calculateTotalProfit(int $Amount, float $singleProfit): float
    {
        return $Amount * $singleProfit;
    }
}
