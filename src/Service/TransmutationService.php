<?php

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
use MZierdt\Albion\repositories\RawResourceRepository;

class TransmutationService extends Market
{
    public function __construct(
        private RawResourceRepository $rawResourceRepository,
        private ConfigService $configService
    ) {
    }

    public function getTransmutationByCity(string $city)
    {
        if (empty($city)) {
            throw new InvalidArgumentException('Please select a city');
        }

        $resources = $this->rawResourceRepository->getRawResourcesByBonusCity($city);
        $transmutationWays = $this->configService->getTransmutationWays();
        dd($transmutationWays);
        foreach ($transmutationWays as $transmutationWay) {
        }

        return $resources;
    }
}