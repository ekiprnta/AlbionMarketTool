<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\Entity\TransmutationEntity;

class TransmutationHelper extends Market
{
    public function reformatResources(array $resources): array
    {
        $formatResource = [];
        foreach ($resources as $resource) {
            $formatResource[$resource->getTier()] = $resource;
        }
        return $formatResource;
    }

    public function transmute(array $transmutationWays, string $startTier, array $cost, float $discount): array
    {
        $transmutation = [];
        foreach ($transmutationWays as $path => $transmutationWay) {
            $currentTier = $startTier;
            $transmutation[$path] = 0;
            foreach ($transmutationWay as $tier) {
                if ($this->sameTier($currentTier, $tier)) {
                    $transmutation[$path] += $this->applyGlobalDiscount($cost[$tier]['enchantment'], $discount);
                } else {
                    $transmutation[$path] += $this->applyGlobalDiscount($cost[$tier]['tier'], $discount);
                    $currentTier++;
                }
            }
        }
        return $transmutation;
    }

    private function sameTier(string $currentTier, string $tier): bool
    {
        return $tier[0] === $currentTier[0];
    }

    public function getEntityList(array $transmutePricing, array $resources, $list): array
    {
        foreach ($transmutePricing as $path => $transmutePrice) {
            [$startTier, $endTier] = $this->getStartAndEndTier($path);

            $list[] = new TransmutationEntity($resources[$startTier], $resources[$endTier], $transmutePrice);
        }
        return $list;
    }

    public function calculateProfit(int $startPrice, int $endPrice, float $transmuteCost): float
    {
        return $endPrice - ($startPrice + $transmuteCost);
    }

    private function applyGlobalDiscount(float $transmuteCost, $globalDiscount): float
    {
        return $transmuteCost * (1 - $globalDiscount);
    }

    private function getStartAndEndTier(string $path): array
    {
        return explode('to', $path);
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

    public function calculateResource(array $resources, string $tier)
    {
        /** @var ResourceEntity $resource */
        foreach ($resources as $resource) {
            if ($resource->getTier() === $tier) {
                return $resource;
            }
        }
    }
}
