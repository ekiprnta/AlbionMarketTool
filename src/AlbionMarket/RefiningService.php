<?php

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntities\RefiningEntity;
use MZierdt\Albion\Entity\ResourceEntity;

class RefiningService extends Market
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

    public function calculateResourceCost(
        int $rawResourcePrice,
        int $lowerResourcePrice,
        int $amountRawResource,
        float $percentage
    ): float {
        $rate = (self::RRR_BASE_PERCENTAGE - $percentage) / 100;
        $resourceCost = $amountRawResource * $rawResourcePrice + $lowerResourcePrice;
        return $rate * $resourceCost;
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

    public function getRefiningRates(): array
    {
        return [
            'No City Bonus No Focus' => 15.2,
            'No City Bonus Focus' => 43.5,
            'City Bonus No Focus' => 36.7,
            'City Bonus Focus' => 53.9,
        ];
    }

    public function calculateRefiningEntity(
        RefiningEntity $refiningEntity,
        array $rawResources,
        array $resources,
        string $city
    ): RefiningEntity {
        $refinedResource = $refiningEntity->getRefinedResource();
        $refiningEntity->setAmountRawResource($this->calculateAmountRawResource($refinedResource->getTier()));
        $refiningEntity->setRawResource($this->calculateResource($refinedResource->getTier(), $rawResources));
        $lowerTier = $this->calculateLowerResourceTier($refinedResource->getTier());
        $refiningEntity->setLowerResource($this->calculateResource($lowerTier, $resources));


        $rawResource = $refiningEntity->getRawResource();
        $lowerResource = $refiningEntity->getLowerResource();
        $refiningEntity->setComplete(
            $this->isComplete(
                [
                    $refinedResource->getSellOrderPrice(),
                    $lowerResource->getBuyOrderPrice(),
                    $lowerResource->getSellOrderPrice(),
                    $rawResource->getBuyOrderPrice(),
                    $rawResource->getSellOrderPrice(),
                ]
            )
        );

        $refiningEntity->setAmount($this->calculateRefiningAmount($refinedResource->getTier()));
        $refiningEntity->setCity($city);

        return $refiningEntity;
    }

    public function calculateProfitByPercentage(RefiningEntity $refiningEntity, float $percentage): RefiningEntity
    {
        $refinedResource = $refiningEntity->getRefinedResource();

        $rawResource = $refiningEntity->getRawResource();
        $lowerResource = $refiningEntity->getLowerResource();
        $refiningEntity->setMaterialCostSell(
            $this->calculateResourceCost(
                $rawResource->getSellOrderPrice(),
                $lowerResource->getSellOrderPrice(),
                $refiningEntity->getAmountRawResource(),
                $percentage
            )
        );
        $refiningEntity->setProfitSell(
            $this->calculateProfit(
                $refinedResource->getSellOrderPrice(),
                $refiningEntity->getMaterialCostSell()
            )
        );
        $refiningEntity->setProfitPercentageSell(
            $this->calculateProfitPercentage(
                $refinedResource->getSellOrderPrice(),
                $refiningEntity->getMaterialCostSell()
            )
        );
        $refiningEntity->setProfitGradeSell(
            $this->calculateProfitGrade($refiningEntity->getProfitPercentageSell())
        );

        $refiningEntity->setMaterialCostBuy(
            $this->calculateResourceCost(
                $rawResource->getBuyOrderPrice(),
                $lowerResource->getBuyOrderPrice(),
                $refiningEntity->getAmountRawResource(),
                $percentage
            )
        );
        $refiningEntity->setProfitBuy(
            $this->calculateProfit($refinedResource->getSellOrderPrice(), $refiningEntity->getMaterialCostBuy())
        );
        $refiningEntity->setProfitPercentageBuy(
            $this->calculateProfitPercentage(
                $refinedResource->getSellOrderPrice(),
                $refiningEntity->getMaterialCostBuy()
            )
        );
        $refiningEntity->setProfitGradeBuy($this->calculateProfitGrade($refiningEntity->getProfitPercentageBuy()));

        return $refiningEntity;
    }
}
