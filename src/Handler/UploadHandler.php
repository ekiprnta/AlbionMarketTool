<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use ECSPrefix202206\React\EventLoop\TimerInterface;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\ResourceUploadRepository;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\ItemHelper;
use MZierdt\Albion\Service\NameDataService;
use MZierdt\Albion\Service\TierService;

class UploadHandler
{


    public function __construct(
        private ApiService $apiService,
        private ResourceUploadRepository $resourceUploadRepository,
        private ItemHelper $itemHelper
    ) {
    }

    public function uploadItemsIntoEmptyDb()
    {
        $items = $this->getAdjustedItems();
    }

    public function uploadResourceIntoEmptyDb(): void
    {
        $resources = $this->getAdjustedResources();

        $this->resourceUploadRepository->loadDataIntoDatabase($resources['metalBar']);
        $this->resourceUploadRepository->loadDataIntoDatabase($resources['planks']);
        $this->resourceUploadRepository->loadDataIntoDatabase($resources['cloth']);
        $this->resourceUploadRepository->loadDataIntoDatabase($resources['leather']);
    }

    public function uploadRefreshedPrices(): void
    {
        $resources = $this->getAdjustedResources();

        $this->resourceUploadRepository->reloadUpdatedPrices($resources['metalBar']);
        $this->resourceUploadRepository->reloadUpdatedPrices($resources['planks']);
        $this->resourceUploadRepository->reloadUpdatedPrices($resources['cloth']);
        $this->resourceUploadRepository->reloadUpdatedPrices($resources['leather']);
    }

    protected function adjustResourceArray(array $resourceArray, string $resourceType)
    {
        $adjustedResourceArray = [];
        foreach ($resourceArray as $resource) {
            $nameAndTier = TierService::splitIntoTierAndName($resource['item_id']);
            $adjustedResourceArray[] = [
                'tier' => $nameAndTier['tier'],
                'name' => $nameAndTier['name'],
                'city' => $resource['city'],
                'sellOrderPrice' => $resource['sell_price_min'],
                'sellOrderPriceDate' => $resource['sell_price_min_date'],
                'buyOrderPrice' => $resource['buy_price_max'],
                'buyOrderPriceDate' => $resource['buy_price_max_date'],
                'bonusCity' => NameDataService::getBonusCityForResource($resourceType)
            ];
        }
        return $adjustedResourceArray;
    }

    public function getAdjustedResources(): array
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

    public function getAdjustedItems(): array
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
}