<?php

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
use MZierdt\Albion\Entity\TransmutationEntity;
use MZierdt\Albion\repositories\RawResourceRepository;

class TransmutationService
{
    public function __construct(
        private RawResourceRepository $rawResourceRepository,
        private TransmutationHelper $transmutationHelper,
        private ConfigService $configService
    ) {
    }

    public function getTransmutationByCity(string $city)
    {
        if (empty($city)) {
            throw new InvalidArgumentException('Please select a city');
        }

        $resources = $this->transmutationHelper->reformatResources(
            $this->rawResourceRepository->getRawResourcesByBonusCity($city)
        );

        $transmutationWays = $this->configService->getTransmutationWays();
        $transmutationCost = $this->configService->getTransmutationCost();

        foreach ($transmutationWays as $key => $transmutationWay) {
            $transmutePricing = $this->transmutationHelper->transmute(
                $transmutationWay,
                $resources[$key],
                $transmutationCost
            );
            $transmutationEntityList = $this->transmutationHelper->getEntityList($transmutePricing, $resources);
        }
        /** @var TransmutationEntity $transmutationEntity */
        foreach ($transmutationEntityList as $transmutationEntity) {
        }
        dd($resources);
        return $resources;
    }
}