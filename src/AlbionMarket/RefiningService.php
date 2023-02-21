<?php

namespace MZierdt\Albion\AlbionMarket;

use InvalidArgumentException;
use MZierdt\Albion\Entity\AdvancedEntities\RefiningEntity;
use MZierdt\Albion\repositories\ResourceRepository;

class RefiningService
{
    private const RRR_BONUS_CITY_NO_FOCUS = 36.7;
    private const RRR_BONUS_CITY_FOCUS = 53.9;
    private const RRR_NO_BONUS_CITY_NO_FOCUS = 15.2;
    private const RRR_NO_BONUS_CITY_FOCUS = 43.5;

    public function __construct(
        private readonly ResourceRepository $resourceRepository,
        private readonly RefiningHelper $refiningHelper,
    ) {
    }

    public function getRefiningForCity(string $itemCity, float $percentage): array
    {
        if (empty($itemCity)) {
            throw new InvalidArgumentException('Please select a city');
        }
        if (empty($percentage)) {
            $percentage = self::RRR_BONUS_CITY_FOCUS;
        }
        $resources = $this->resourceRepository->getResourcesByBonusCity($itemCity);
        $rawResources = $this->resourceRepository->getRawResourcesByBonusCity($itemCity);

        $refiningArray = [];
        foreach ($resources as $resource) {
            if ($resource->getTier() !== 20) {
                $refiningArray[] = new RefiningEntity($resource);
            }
        }
        /** @var RefiningEntity $refiningEntity */
        foreach ($refiningArray as $refiningEntity) {
            $refiningEntity->setAmountRawResource(
                $this->refiningHelper->calculateAmountRawResource($refiningEntity->getRefinedResource()->getTier())
            );
            $refiningEntity->setRawResource(
                $this->refiningHelper->calculateResource(
                    $refiningEntity->getRefinedResource()->getTier(),
                    $rawResources
                )
            );
            $lowerTier = $this->refiningHelper->calculateLowerResourceTier(
                $refiningEntity->getRefinedResource()
                    ->getTier()
            );
            $refiningEntity->setLowerResource($this->refiningHelper->calculateResource($lowerTier, $resources));

            $resourceCost = $this->refiningHelper->calculateResourceCost(
                $refiningEntity->getRawResource()
                    ->getSellOrderPrice(),
                $refiningEntity->getLowerResource()
                    ->getSellOrderPrice(),
                $refiningEntity->getAmountRawResource(),
                $percentage
            );
            $refiningEntity->setSingleProfit(
                $this->refiningHelper->calculateProfit(
                    $refiningEntity->getRefinedResource()
                        ->getSellOrderPrice(),
                    $resourceCost
                )
            );
            $refiningEntity->setAmount(
                $this->refiningHelper->calculateRefiningAmount($refiningEntity->getRefinedResource()->getTier())
            );
            $refiningEntity->setProfit(
                $this->refiningHelper->calculateTotalProfit(
                    $refiningEntity->getAmount(),
                    $refiningEntity->getSingleProfit()
                )
            );
            $refiningEntity->setProfitPercentage(
                $this->refiningHelper->calculateProfitPercentage(
                    $refiningEntity->getRefinedResource()
                        ->getSellOrderPrice(),
                    $resourceCost
                )
            );
            $refiningEntity->setProfitGrade(
                $this->refiningHelper->calculateProfitGrade($refiningEntity->getProfitPercentage())
            );
        }
        return $refiningArray;
    }

    public function getRefiningRates(): array
    {
        return [
            'No City Bonus No Focus' => self::RRR_NO_BONUS_CITY_NO_FOCUS,
            'No City Bonus Focus' => self::RRR_NO_BONUS_CITY_FOCUS,
            'City Bonus No Focus' => self::RRR_BONUS_CITY_NO_FOCUS,
            'City Bonus Focus' => self::RRR_BONUS_CITY_FOCUS,
        ];
    }
}
