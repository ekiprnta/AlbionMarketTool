<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\MaterialEntity;

class CapesCraftingHelper extends Market
{
    public function calculateDefaultCape(int $tier, array $capes)
    {
        foreach ($capes as $cape) {
            if ($cape->getTier() === $tier) {
                return $cape;
            }
        }
        throw new \InvalidArgumentException('No Tier found in calculateDefaultCape ' . $tier);
    }

    public function calculateSecondResource(
        string $resourceName,
        int $tier,
        array $heartsAndSigils
    ): MaterialEntity {
        /** @var MaterialEntity $heartAndSigil */
        foreach ($heartsAndSigils as $heartAndSigil) {
            if ($heartAndSigil->getTier() === 10) {
                $newTier = $heartAndSigil->getTier();
            } else {
                $newTier = $tier;
            }
            if ($heartAndSigil->getTier() === $newTier && $heartAndSigil->getRealName() === $resourceName) {
                return $heartAndSigil;
            }
        }
        throw new \InvalidArgumentException(
            'No Material found in calculateSecondResource ' . $tier . ':' . $resourceName
        );
    }

    public function calculateArtifact(string $artifactName, int $tier, array $artifacts): ?MaterialEntity
    {
        $baseTier = (int) ($tier / 10);
        /** @var MaterialEntity $artifact */
        foreach ($artifacts as $artifact) {
            if ($artifact->getTier() === ($baseTier * 10) && $artifact->getName() === $artifactName) {
                return $artifact;
            }
        }
        return null;
    }

    public function calculateMaterialCost(
        int $primaryItemCost,
        int $secondaryMaterialCost,
        int $secondaryMaterialAmount,
        int $artifactCost
    ): float {
        return $this->calculateBuyOrder(
                $primaryItemCost
            ) + ($secondaryMaterialCost * $secondaryMaterialAmount) + $this->calculateBuyOrder($artifactCost);
    }

    public function calculateProfit(int $specialCapePrice, float $materialCost): float
    {
        return $specialCapePrice - $materialCost;
    }
}
