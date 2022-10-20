<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use ECSPrefix202206\React\EventLoop\TimerInterface;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\ResourceUploadRepository;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\NameDataService;
use MZierdt\Albion\Service\TierService;

class UploadHandler
{


    public function __construct(
        private ApiService $apiService,
        private ResourceUploadRepository $resourceUploadRepository,
    ) {
    }

    public function uploadResourceIntoDb(): void
    {
        $metalBarArray = $this->apiService->getResource(ResourceEntity::RESOURCE_METAL_BAR);
        $metalBarArrayAdjusted = $this->adjustResourceArray($metalBarArray, ResourceEntity::RESOURCE_METAL_BAR);
        $planksArray = $this->apiService->getResource(ResourceEntity::RESOURCE_PLANKS);
        $planksArrayAdjusted = $this->adjustResourceArray($planksArray, ResourceEntity::RESOURCE_PLANKS);
        $clothArray = $this->apiService->getResource(ResourceEntity::RESOURCE_CLOTH);
        $clothArrayAdjusted = $this->adjustResourceArray($clothArray, ResourceEntity::RESOURCE_CLOTH);
        $leatherArray = $this->apiService->getResource(ResourceEntity::RESOURCE_LEATHER);
        $leatherArrayAdjusted = $this->adjustResourceArray($leatherArray, ResourceEntity::RESOURCE_LEATHER);

        $this->resourceUploadRepository->loadDataIntoDatabase($metalBarArrayAdjusted);
        $this->resourceUploadRepository->loadDataIntoDatabase($planksArrayAdjusted);
        $this->resourceUploadRepository->loadDataIntoDatabase($clothArrayAdjusted);
        $this->resourceUploadRepository->loadDataIntoDatabase($leatherArrayAdjusted);
    }

    protected function adjustResourceArray(array $resourceArray, string $resourceType)
    {
        $adjustedRessourceArray = [];
        foreach ($resourceArray as $resource) {
            $nameAndTier = TierService::splitIntoTierAndName($resource['item_id']);
            $adjustedRessourceArray[] = [
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
        return $adjustedRessourceArray;
    }
}