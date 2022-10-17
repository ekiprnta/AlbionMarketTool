<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use League\Csv\Writer;
use MZierdt\Albion\Service\ApiService;

class MageUploadRepository implements UploadInterface
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

        $helmetArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_HELMET);
        $armorArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_ARMOR);
        $bootsArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_BOOTS);
        $fireArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_FIRE_STAFF);
        $holyArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_HOLY_STAFF);
        $arcaneArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_ARCANE_STAFF);
        $frostArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_FROST_STAFF);
        $curseArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_CURSE_STAFF);
        $tomeArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_TOME_STAFF);

        $mageArray = array_merge(
            $helmetArray,
            $armorArray,
            $bootsArray,
            $fireArray,
            $holyArray,
            $arcaneArray,
            $frostArray,
            $curseArray,
            $tomeArray,
        );

        $filteredMageArray = $this->filterArrays($mageArray);

        $csv = $this->getCsvConnection();
        $csv->insertOne($header);
        $csv->insertAll($filteredMageArray);
    }

    private function getCsvConnection(): Writer
    {
        return Writer::createFromPath(self::PATH_TO_CSV, 'wb');
    }

    private function filterArrays(array $data): array
    {
        $filteredArray = [];
        foreach ($data as $mageInfo) {
            $filteredArray[] = [
                $mageInfo['item_id'],
                $mageInfo['city'],
                $mageInfo['quality'],
                $mageInfo['sell_price_min'],
                $mageInfo['sell_price_min_date'],
                $mageInfo['buy_price_max'],
                $mageInfo['buy_price_max_date'],
            ];
        }
        return $filteredArray;
    }
}