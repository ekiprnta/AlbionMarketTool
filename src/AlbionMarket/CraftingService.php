<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionMarket;

use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\AdvancedEntities\RefiningEntity;
use MZierdt\Albion\repositories\AdvancedRepository\BlackMarketCraftingRepository;
use MZierdt\Albion\repositories\AdvancedRepository\RefiningRepository;

class CraftingService extends Market
{
    public function __construct(
        private readonly BlackMarketCraftingRepository $blackMarketCraftingRepository,
        private readonly BlackMarketCraftingService $blackMarketCraftingService,
        private readonly RefiningRepository $refiningRepository,
        private readonly RefiningService $refiningService,
    ) {
    }

    public function getAllBmCraftingByCity(string $itemCity, float $percentage): array
    {
        if (empty($percentage)) {
            $percentage = 47.9;
        }

        $bmCraftingEntities = $this->blackMarketCraftingRepository->getAllBmCraftingByCity($itemCity);

        /** @var BlackMarketCraftingEntity $bmcEntity */
        foreach ($bmCraftingEntities as $bmcEntity) {
            $this->blackMarketCraftingService->calculateProfitByPercentage($bmcEntity, $percentage);
        }

        return $bmCraftingEntities;
    }

    public function getAllRefiningByCity(string $itemCity, float $percentage): array
    {
        if (empty($percentage)) {
            $percentage = 53.9;
        }

        $refiningEntities = $this->refiningRepository->getAllRefiningByCity($itemCity);

        /** @var RefiningEntity $refiningEntity */
        foreach ($refiningEntities as $refiningEntity) {
            $this->refiningService->calculateProfitByPercentage($refiningEntity, $percentage);
        }
        return $refiningEntities;
    }
}