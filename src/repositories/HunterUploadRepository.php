<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use League\Csv\Writer;
use MZierdt\Albion\Service\ApiService;

class HunterUploadRepository implements UploadInterface
{
    private const PATH_TO_CSV = __DIR__ . '/../../assets/mage.csv';

    public function __construct(
        private ApiService $apiService
    ) {
    }

    public function uploadIntoCsv()
    {
        $header = [
            'itemId',
            'city',
            'quality',
            'sellOrderPrice',
            'sellOrderPriceDate',
            'buyOrderPrice',
            'buyOrderPriceDate'
        ];

        $helmetArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_HELMET);
        $armorArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_ARMOR);
        $bootsArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_BOOTS);
        $bowArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_BOW);
        $spearArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_SPEAR);
        $natureArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_NATURE_STAFF);
        $daggerArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_DAGGER);
        $quarterstaffArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_QUARTERSTAFF);
        $torchArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_TORCH);

        $hunterArray = array_merge(
            $helmetArray,
            $armorArray,
            $bootsArray,
            $bowArray,
            $spearArray,
            $natureArray,
            $daggerArray,
            $quarterstaffArray,
            $torchArray,
        );

        $filteredHunterArray = $this->filterArrays($hunterArray);

        $csv = $this->getCsvConnection();
        $csv->insertOne($header);
        $csv->insertAll($filteredHunterArray);
    }

    private function getCsvConnection(): Writer
    {
        return Writer::createFromPath(self::PATH_TO_CSV, 'wb');
    }

    private function filterArrays(array $data): array
    {
        $filteredArray = [];
        foreach ($data as $hunterInfo) {
            $filteredArray[] = [
                $hunterInfo['item_id'],
                $hunterInfo['city'],
                $hunterInfo['quality'],
                $hunterInfo['sell_price_min'],
                $hunterInfo['sell_price_min_date'],
                $hunterInfo['buy_price_max'],
                $hunterInfo['buy_price_max_date'],
            ];
        }
        return $filteredArray;
    }
}