<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use League\Csv\Writer;
use MZierdt\Albion\Service\ApiService;

class ResourceRepositoryUpload
{
    private const PATH_TO_CSV = __DIR__ . '/../../assets/ressourcen.csv';

    public function __construct(
        private ApiService $apiService,
    ) {
    }


    public function uploadIntoCsv()
    {
        $header = ['itemId', 'city', 'sellOrderPrice', 'sellOrderPriceDate', 'buyOrderPrice', 'buyOrderPriceDate'];

        $metalBarArray = $this->apiService->getResource('metalBar');
        $planksArray = $this->apiService->getResource('planks');
        $clothArray = $this->apiService->getResource('cloth');
        $leatherArray = $this->apiService->getResource('leather');

        $resourceArray = $metalBarArray + $planksArray + $clothArray + $leatherArray;
        $resourceArray = $this->filterArrays($resourceArray);

        $csv = $this->getCsvConnection();
        $csv->insertOne($header);
        $csv->insertAll($resourceArray);
    }

    private function getCsvConnection(): Writer
    {
        return Writer::createFromPath(self::PATH_TO_CSV, 'wb');
    }

    private function filterArrays(array $resourceArray)
    {
        $filteredArray = [];
        foreach ($resourceArray as $resourceInfo) {
            $filteredArray[] = [
                $resourceInfo['item_id'],
                $resourceInfo['city'],
                $resourceInfo['sell_price_min'],
                $resourceInfo['sell_price_min_date'],
                $resourceInfo['buy_price_max'],
                $resourceInfo['buy_price_max_date'],
            ];
        }
        return $filteredArray;
    }
}