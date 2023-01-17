<?php

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
use MZierdt\Albion\repositories\RawResourceRepository;

class TransmutationService
{
    public function __construct(
        private RawResourceRepository $rawResourceRepository,
        private TransmutationHelper $transmutationHelper,
        private ConfigService $configService,
        private GlobalDiscountService $discountService,
    ) {
    }

    public function getTransmutationByCity(string $city): array
    {
        if (empty($city)) {
            throw new InvalidArgumentException('Please select a city');
        }

        $resources = $this->transmutationHelper->reformatResources(
            $this->rawResourceRepository->getRawResourcesByBonusCity($city)
        );

        $transmutationWays = $this->configService->getTransmutationWays();
        $transmutationCost = $this->configService->getTransmutationCost();

        $transmutationEntityList = [];
        foreach ($transmutationWays as $key => $transmutationWay) {
            $transmutePricing = $this->transmutationHelper->transmute(
                $transmutationWay,
                $resources[$key],
                $transmutationCost,
                $this->discountService->getGlobalDiscount(),
            );
            $transmutationEntityList = $this->transmutationHelper->getEntityList(
                $transmutePricing,
                $resources,
                $transmutationEntityList
            );
        }
        return $transmutationEntityList;
    }
}