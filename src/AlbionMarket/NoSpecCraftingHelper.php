<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;

class NoSpecCraftingHelper extends Market
{
    public function calculateDefaultItem(int $tier, string $name, array $capesAndArmor): ItemEntity
    {
        /** @var ItemEntity $item */
        foreach ($capesAndArmor as $item) {
            if ($item->getTier() === $tier && $item->getName() === $name) {
                return $item;
            }
        }
        throw new \InvalidArgumentException('No Tier found in calculateDefaultCape ' . $tier . ':' . $name);
    }

    public function calculateSecondResource(
        string $resourceName,
        int $tier,
        array $heartsAndSigils
    ): MaterialEntity {
        $tier = (int) ($tier / 10) * 10;
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

    public function calculateArtifact(?string $artifactName, int $tier, array $artifacts): ?MaterialEntity
    {
        if ($artifactName === null) {
            return null;
        }
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
        return $primaryItemCost + ($secondaryMaterialCost * $secondaryMaterialAmount) + ($artifactCost);
    }

    public function calculateProfit(int $specialCapePrice, float $materialCost): float
    {
        return $specialCapePrice - $materialCost;
    }
}
