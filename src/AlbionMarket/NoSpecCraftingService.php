<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntities\NoSpecEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;

class NoSpecCraftingService extends Market
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
        float $primaryItemCost,
        float $secondaryMaterialCost,
        int $secondaryMaterialAmount,
        float $artifactCost
    ): float {
        return $primaryItemCost + ($secondaryMaterialCost * $secondaryMaterialAmount) + ($artifactCost);
    }

    public function calculateNoSpecEntity(
        NoSpecEntity $noSpecEntity,
        array $defaultItems,
        array $heartsAndSigils,
        array $artifacts,
        string $city
    ): NoSpecEntity {
        $specialItem = $noSpecEntity->getSpecialItem();
        $noSpecEntity->setDefaultItem(
            $this->calculateDefaultItem($specialItem->getTier(), $specialItem->getPrimaryResource(), $defaultItems)
        );

        $noSpecEntity->setSecondResource(
            $this->calculateSecondResource(
                $specialItem->getSecondaryResource(),
                $specialItem->getTier(),
                $heartsAndSigils
            )
        );
        $noSpecEntity->setArtifact(
            $this->calculateArtifact($specialItem->getArtifact(), $specialItem->getTier(), $artifacts)
        );
        if ($noSpecEntity->getArtifact() === null) {
            $artifactPrice = 0;
        } else {
            $artifactPrice = $this->calculateBuyOrder($noSpecEntity->getArtifact()->getBuyOrderPrice());
        }

        $defaultItem = $noSpecEntity->getDefaultItem();
        $secondResource = $noSpecEntity->getSecondResource();
        $noSpecEntity->setMaterialCostSell(
            $this->calculateMaterialCost(
                $defaultItem->getSellOrderPrice(),
                $secondResource->getSellOrderPrice(),
                $specialItem->getSecondaryResourceAmount(),
                $artifactPrice
            )
        );
        $noSpecEntity->setProfitSell(
            $this->calculateProfit($specialItem->getSellOrderPrice(), $noSpecEntity->getMaterialCostSell())
        );
        $noSpecEntity->setProfitPercentageSell(
            $this->calculateProfitPercentage(
                $specialItem->getSellOrderPrice(),
                $noSpecEntity->getMaterialCostSell()
            )
        );
        $noSpecEntity->setProfitGradeSell($this->calculateProfitGrade($noSpecEntity->getProfitPercentageSell()));

        $noSpecEntity->setMaterialCostBuy(
            $this->calculateMaterialCost(
                $this->calculateBuyOrder($defaultItem->getBuyOrderPrice()),
                $this->calculateBuyOrder($secondResource->getBuyOrderPrice()),
                $specialItem->getSecondaryResourceAmount(),
                $artifactPrice
            )
        );
        $noSpecEntity->setProfitBuy(
            $this->calculateProfit($specialItem->getSellOrderPrice(), $noSpecEntity->getMaterialCostBuy())
        );
        $noSpecEntity->setProfitPercentageBuy(
            $this->calculateProfitPercentage($specialItem->getSellOrderPrice(), $noSpecEntity->getMaterialCostBuy())
        );
        $noSpecEntity->setProfitGradeBuy($this->calculateProfitGrade($noSpecEntity->getProfitPercentageBuy()));

        $noSpecEntity->setComplete(
            $this->isComplete(
                [
                    $specialItem->getSellOrderPrice(),
                    $defaultItem->getSellOrderPrice(),
                    $defaultItem->getBuyOrderPrice(),
                    $secondResource->getSellOrderPrice(),
                    $secondResource->getBuyOrderPrice(),
                    $artifactPrice,
                ]
            )
        );
        $noSpecEntity->setCity($city);

        return $noSpecEntity;
    }
}
