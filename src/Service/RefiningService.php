<?php

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
use MZierdt\Albion\Entity\RefiningEntity;
use MZierdt\Albion\repositories\RawResourceRepository;
use MZierdt\Albion\repositories\ResourceRepository;

class RefiningService
{
    public function __construct(
        private ResourceRepository $resourceRepository,
        private RawResourceRepository $rawRepository,
        private RefiningHelper $refiningHelper,
    ) {
    }

    public function getRefiningForCity(string $itemCity, float $percentage): array
    {
        if (empty($itemCity)) {
            throw new InvalidArgumentException('Please select a city');
        }
        if (empty($percentage)) {
            $percentage = 47.9;
        }

        $resources = $this->resourceRepository->getResourcesByBonusCity($itemCity);
        $rawResources = $this->rawRepository->getRawResourcesByBonusCity($itemCity);

        $refiningArray = [];
        foreach ($resources as $resource) {
            if ($resource->getTier() !== '2') {
                $refiningArray[] = new RefiningEntity($resource);
            }
        }

        /** @var RefiningEntity $refiningEntity */
        foreach ($refiningArray as $refiningEntity) {
            $refiningEntity->setAmountRawResource(
                $this->refiningHelper->calculateAmountRawResource($refiningEntity->getResourceEntity()->getTier())
            );
            $refiningEntity->setRawResource(
                $this->refiningHelper->calculateResource($refiningEntity->getResourceEntity()->getTier(), $rawResources)
            );
            $refiningEntity->setLowerResource(
                $this->refiningHelper->calculateLowerResource(
                    $refiningEntity->getResourceEntity()->getTier(),
                    $resources
                )
            );
            $refiningEntity->setSingleProfit(
                $this->refiningHelper->calculateProfit(
                    $refiningEntity->getResourceEntity()->getSellOrderPrice(),
                    $refiningEntity->getRawResource()->getSellOrderPrice(),
                    $refiningEntity->getLowerResource()->getSellOrderPrice(),
                    $refiningEntity->getAmountRawResource(),
                    $percentage
                )
            );
            $refiningEntity->setAmount(
                $this->refiningHelper->calculateRefiningAmount($refiningEntity->getResourceEntity()->getTier())
            );
            $refiningEntity->setProfit(
                $this->refiningHelper->calculateTotalProfit(
                    $refiningEntity->getAmount(),
                    $refiningEntity->getSingleProfit()
                )
            );
            $refiningEntity->setWeightAmountQuotient(
                $this->refiningHelper->calculateWeightProfitQuotient(
                    $refiningEntity->getProfit(),
                    $refiningEntity->getAmount()
                )
            );
            $refiningEntity->setProfitGrade($refiningEntity->getWeightAmountQuotient());
        }
        return $refiningArray;
    }


}