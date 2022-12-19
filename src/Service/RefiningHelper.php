<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ResourceEntity;

class RefiningHelper extends Market
{
    public function calculateAmountRawResource(string $tier): int
    {
        return match ($tier[0]) {
            '3', '4' => 2,
            '5' => 3,
            '6' => 4,
            '7', '8' => 5,
            default => 0,
        };
    }

    public function calculateResource(string $tier, array $rawResources): ResourceEntity
    {
        /** @var ResourceEntity $rawResource */
        foreach ($rawResources as $rawResource) {
            if ($rawResource->getTier() === $tier) {
                return $rawResource;
            }
        }
        throw new \InvalidArgumentException('No Resource found for ' . $tier);
    }

    public function calculateLowerResource(string $tier, array $resources): ResourceEntity
    {
        $lowerMainTier = (int) $tier[0] - 1;
        if (strlen($tier) > 1) {
            $subTier = substr($tier, -1);
            $tier = $lowerMainTier . $subTier;
        } else {
            $tier = (string) $lowerMainTier;
        }
        if (str_starts_with($tier, '3') && strlen($tier) > 1) {
            $tier = $tier[0];
        }

        return $this->calculateResource($tier, $resources);
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

    public function calculateRefiningAmount(string $tier): int
    {
        return match ($tier) {
            '3' => 1250,
            '4' => 10000,
            '41', '5' => 5000,
            '42' => 3333,
            '43' => 2000,
            '51', '6' => 3000,
            '52' => 1765,
            '53' => 1035,
            '61', '7' => 1667,
            '62' => 1000,
            '63' => 566,
            '71', '8' => 968,
            '72' => 556,
            '73' => 319,
            '81' => 545,
            '82' => 316,
            '83' => 180,
            default => throw new \InvalidArgumentException('Wrong tier: ' . $tier),
        };
    }

    public function calculateTotalProfit(int $Amount, float $singleProfit): float
    {
        return $Amount * $singleProfit;
    }
}