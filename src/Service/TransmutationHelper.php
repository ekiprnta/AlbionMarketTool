<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ResourceEntity;

class TransmutationHelper extends Market
{

    private function sameTier(string $currentTier, string $tier): bool
    {
        return $tier[0] === $currentTier[0];
    }

    public function calculateProfit(int $startPrice, int $endPrice, float $transmuteCost): float
    {
        return $endPrice - ($startPrice + $transmuteCost);
    }

    private function applyGlobalDiscount(float $transmuteCost, $globalDiscount): float
    {
        return $transmuteCost * (1 - $globalDiscount);
    }

    public function calculateStartAndEnd(string $pathName): array
    {
        return explode('to', $pathName);
    }

    public function calculateTransmutationPrice(
        array $transmutationPath,
        string $startTier,
        array $transmutationCost,
        float $discount
    ): float {
        $currentTier = $startTier;
        $cost = 0;
        foreach ($transmutationPath as $transmutationStep) {
            if ($this->sameTier($currentTier, $transmutationStep)) {
                $cost += $this->applyGlobalDiscount($transmutationCost[$transmutationStep]['enchantment'], $discount);
            } else {
                $cost += $this->applyGlobalDiscount($transmutationCost[$transmutationStep]['tier'], $discount);
                ++$currentTier;
            }
        }
        return $cost;
    }

    public function calculateResource(array $resources, string $tier, string $name): ?ResourceEntity
    {
        /** @var ResourceEntity $resource */
        foreach ($resources as $resource) {
            if ($resource->getTier() === $tier && $resource->getRealName() === $name) {
                return $resource;
            }
        }
        return null;
    }
}
