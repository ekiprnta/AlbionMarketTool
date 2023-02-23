<?php

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntities\TransmutationEntity;
use MZierdt\Albion\Entity\ResourceEntity;

class TransmutationService extends Market
{
    private function sameTier(string $currentTier, string $tier): bool
    {
        return $tier[0] === $currentTier[0];
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

    public function calculateResource(array $resources, int $tier, string $name): ?ResourceEntity
    {
        /** @var ResourceEntity $resource */
        foreach ($resources as $resource) {
            if ($resource->getTier() === $tier && $resource->getRealName() === $name) {
                return $resource;
            }
        }
        return null;
    }

    public function calculateTransmutationEntity(
        TransmutationEntity $transEntity,
        array $resources,
        mixed $startTier,
        mixed $endTier,
        array $transmutationCost,
        float $globalDiscount,
        string $city
    ): void {
        $transEntity->setStartResource(
            $this->calculateResource(
                $resources,
                (int) $startTier,
                $transEntity->getResourceType()
            )
        );
        $transEntity->setEndResource(
            $this->calculateResource(
                $resources,
                (int) $endTier,
                $transEntity->getResourceType()
            )
        );
        $transEntity->setTransmutationPrice(
            $this->calculateTransmutationPrice(
                $transEntity->getTransmutationPath(),
                $startTier,
                $transmutationCost,
                $globalDiscount
            )
        );

        $transEntity->setMaterialCostSell(
            $transEntity->getStartResource()
                ->getSellOrderPrice() + $transEntity->getTransmutationPrice()
        );
        $transEntity->setProfitSell(
            $this->calculateProfit(
                $transEntity->getEndResource()
                    ->getBuyOrderPrice(),
                $transEntity->getMaterialCostSell()
            )
        );
        $transEntity->setProfitPercentageSell(
            $this->calculateProfitPercentage(
                $transEntity->getEndResource()
                    ->getSellOrderPrice(),
                $transEntity->getMaterialCostSell()
            )
        );
        $transEntity->setProfitGradeSell(
            $this->calculateProfitGrade($transEntity->getProfitPercentageSell())
        );

        $transEntity->setMaterialCostBuy(
            $transEntity->getStartResource()
                ->getBuyOrderPrice() + $transEntity->getTransmutationPrice()
        );
        $transEntity->setProfitBuy(
            $this->calculateProfit(
                $transEntity->getEndResource()
                    ->getSellOrderPrice(),
                $transEntity->getMaterialCostBuy()
            )
        );
        $transEntity->setProfitPercentageBuy(
            $this->calculateProfitPercentage(
                $transEntity->getEndResource()
                    ->getSellOrderPrice(),
                $transEntity->getMaterialCostBuy()
            )
        );
        $transEntity->setProfitGradeBuy(
            $this->calculateProfitGrade($transEntity->getProfitPercentageBuy())
        );

        $transEntity->setTierColor((int) ($transEntity->getStartResource()->getTier() / 10));
        $transEntity->setEndTierColor((int) ($transEntity->getEndResource()->getTier() / 10));

        $transEntity->setComplete(
            $this->isComplete([
                $transEntity->getEndResource()
                    ->getSellOrderPrice(),
                $transEntity->getStartResource()
                    ->getSellOrderPrice(),
                $transEntity->getStartResource()
                    ->getBuyOrderPrice(),
                $transEntity->getTransmutationPrice(),
            ])
        );
        $transEntity->setCity($city);
    }
}
