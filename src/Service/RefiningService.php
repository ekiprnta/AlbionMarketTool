<?php

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
use MZierdt\Albion\Handler\RefiningHandler;
use MZierdt\Albion\repositories\RawResourceRepository;
use MZierdt\Albion\repositories\ResourceRepository;

class RefiningService
{
    public function __construct(
        private ResourceRepository $resourceRepository,
        private RawResourceRepository $rawRepository,
        private RefiningHandler $refiningHandler,
    ) {
    }

    public function getRefiningForCity(string $itemCity, int $feeProHundredNutrition): array
    {
        if (empty($itemCity)) {
            throw new InvalidArgumentException('Please select a city');
        }
        if (empty($feeProHundredNutrition)) {
            $feeProHundredNutrition = 0;
        }

        $resources = $this->resourceRepository->getResourcesByBonusCity($itemCity);
        $rawResources = $this->rawRepository->getRawResourcesByBonusCity($itemCity);

        dd($resources, $rawResources);

        return [];
    }


}