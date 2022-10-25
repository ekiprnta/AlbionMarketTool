<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\UploadRepository;

class UploadService
{
    public function __construct(
        private ApiService $apiService,
        private UploadRepository $UploadRepository,
        private ItemHelper $itemHelper
    ) {
    }

    public function uploadItemsIntoEmptyDb(): void
    {
        $items = $this->getAdjustedItems();

        $this->UploadRepository->loadItemsIntoDatabase($items['warrior']);
        $this->UploadRepository->loadItemsIntoDatabase($items['mage']);
        $this->UploadRepository->loadItemsIntoDatabase($items['hunter']);
    }

    public function uploadResourceIntoEmptyDb(): void
    {
        $resources = $this->getAdjustedResources();

        $this->UploadRepository->loadResourcesIntoDatabase($resources['metalBar']);
        $this->UploadRepository->loadResourcesIntoDatabase($resources['planks']);
        $this->UploadRepository->loadResourcesIntoDatabase($resources['cloth']);
        $this->UploadRepository->loadResourcesIntoDatabase($resources['leather']);
    }

    public function uploadRefreshedPrices(): void
    {
        $resources = $this->getAdjustedResources();

        $this->UploadRepository->reloadUpdatedPricesResources($resources['metalBar']);
        $this->UploadRepository->reloadUpdatedPricesResources($resources['planks']);
        $this->UploadRepository->reloadUpdatedPricesResources($resources['cloth']);
        $this->UploadRepository->reloadUpdatedPricesResources($resources['leather']);

        $items = $this->getAdjustedItems();

        $this->UploadRepository->reloadUpdatedPricesItems($items['warrior']);
        $this->UploadRepository->reloadUpdatedPricesItems($items['mage']);
        $this->UploadRepository->reloadUpdatedPricesItems($items['hunter']);
    }

    protected function adjustResourceArray(array $resourceArray, string $resourceType)
    {
        $adjustedResourceArray = [];
        foreach ($resourceArray as $resource) {
            $nameAndTier = TierService::splitIntoTierAndName($resource['item_id']);
            $name = $this->getResourceName($nameAndTier['name']);
            $adjustedResourceArray[] = [
                'tier' => $nameAndTier['tier'],
                'name' => $name,
                'city' => $resource['city'],
                'sellOrderPrice' => $resource['sell_price_min'],
                'sellOrderPriceDate' => $resource['sell_price_min_date'],
                'buyOrderPrice' => $resource['buy_price_max'],
                'buyOrderPriceDate' => $resource['buy_price_max_date'],
                'bonusCity' => NameDataService::getBonusCityForResource($resourceType),
            ];
        }
        return $adjustedResourceArray;
    }

    private function getAdjustedResources(): array
    {
        $metalBarArray = $this->apiService->getResource(ResourceEntity::RESOURCE_METAL_BAR);
        $metalBarArrayAdjusted = $this->adjustResourceArray($metalBarArray, ResourceEntity::RESOURCE_METAL_BAR);
        $planksArray = $this->apiService->getResource(ResourceEntity::RESOURCE_PLANKS);
        $planksArrayAdjusted = $this->adjustResourceArray($planksArray, ResourceEntity::RESOURCE_PLANKS);
        $clothArray = $this->apiService->getResource(ResourceEntity::RESOURCE_CLOTH);
        $clothArrayAdjusted = $this->adjustResourceArray($clothArray, ResourceEntity::RESOURCE_CLOTH);
        $leatherArray = $this->apiService->getResource(ResourceEntity::RESOURCE_LEATHER);
        $leatherArrayAdjusted = $this->adjustResourceArray($leatherArray, ResourceEntity::RESOURCE_LEATHER);

        return [
            'metalBar' => $metalBarArrayAdjusted,
            'planks' => $planksArrayAdjusted,
            'cloth' => $clothArrayAdjusted,
            'leather' => $leatherArrayAdjusted,
        ];
    }

    private function getAdjustedItems(): array
    {
        $warriorArray = $this->itemHelper->getWarriorItems();
        $warriorArrayAdjusted = $this->adjustItemsArray($warriorArray, ItemEntity::CLASS_WARRIOR);
        $mageArray = $this->itemHelper->getMageItems();
        $mageArrayAdjusted = $this->adjustItemsArray($mageArray, ItemEntity::CLASS_MAGE);
        $hunterArray = $this->itemHelper->getHunterItems();
        $hunterArrayAdjusted = $this->adjustItemsArray($hunterArray, ItemEntity::CLASS_HUNTER);

        return [
            'warrior' => $warriorArrayAdjusted,
            'mage' => $mageArrayAdjusted,
            'hunter' => $hunterArrayAdjusted,
        ];
    }

    private function adjustItemsArray(array $classArray, string $class): array
    {
        $adjustedItemsArray = [];
        foreach ($classArray as $weaponGroupName => $weaponGroup) {
            foreach ($weaponGroup as $weapon) {
                foreach ($weapon as $item) {
                    $nameAndTier = TierService::splitIntoTierAndName($item['item_id']);
                    $stats = NameDataService::getStatsForItem($class, $weaponGroupName, $nameAndTier['name']);
                    $adjustedItemsArray[] = [
                        'tier' => $nameAndTier['tier'],
                        'name' => $nameAndTier['name'],
                        'weaponGroup' => $weaponGroupName,
                        'class' => $class,
                        'city' => $item['city'],
                        'quality' => $item['quality'],
                        'sellOrderPrice' => $item['sell_price_min'],
                        'sellOrderPriceDate' => $item['sell_price_min_date'],
                        'buyOrderPrice' => $item['buy_price_max'],
                        'buyOrderPriceDate' => $item['buy_price_max_date'],
                        'primaryResource' => $stats['primaryResource'],
                        'primaryResourceAmount' => $stats['primaryResourceAmount'],
                        'secondaryResource' => $stats['secondaryResource'],
                        'secondaryResourceAmount' => $stats['secondaryResourceAmount'],
                        'bonusCity' => $stats['bonusCity'],
                        'fameFactor' => null,
                    ];
                }
            }
        }
        return $adjustedItemsArray;
    }

    private function getResourceName(string $name): string
    {
        if (str_contains($name, '_level1')) {
            $resourceName = str_replace('_level1', '', $name);
        }
        if (str_contains($name, '_level2')) {
            $resourceName = str_replace('_level2', '', $name);
        }
        if (str_contains($name, '_level3')) {
            $resourceName = str_replace('_level3', '', $name);
        }
        return $resourceName ?? $name;
    }
}
