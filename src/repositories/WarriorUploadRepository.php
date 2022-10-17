<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use League\Csv\Writer;
use MZierdt\Albion\Service\ApiService;

class WarriorUploadRepository implements UploadInterface
{
    private const PATH_TO_CSV = __DIR__ . '/../../assets/warrior.csv';

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

        $helmetArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_HELMET);
//        $armorArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_ARMOR);
//        $bootsArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_BOOTS);
//        $swordArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_SWORD);
//        $axeArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_AXE);
//        $maceArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_MACE);
//        $hammerArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_HAMMER);
//        $warGloveArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_WAR_GLOVE);
//        $crossbowArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_CROSSBOW);
//        $shieldArray = $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_SHIELD);

        $warriorArray = array_merge(
            $helmetArray,
//            $armorArray,
//            $bootsArray,
//            $swordArray,
//            $axeArray,
//            $maceArray,
//            $hammerArray,
//            $warGloveArray,
//            $crossbowArray,
//            $shieldArray,
        );
        dd($warriorArray);

        $filteredWarriorArray = $this->filterArrays($warriorArray);

        $csv = $this->getCsvConnection();
        $csv->insertOne($header);
        $csv->insertAll($filteredWarriorArray);
    }

    private function getCsvConnection(): Writer
    {
        return Writer::createFromPath(self::PATH_TO_CSV, 'wb');
    }

    private function filterArrays(array $data): array
    {
        $filteredArray = [];
        foreach ($data as $warriorInfo) {
            $filteredArray[] = [
                $warriorInfo['item_id'],
                $warriorInfo['city'],
                $warriorInfo['quality'],
                $warriorInfo['sell_price_min'],
                $warriorInfo['sell_price_min_date'],
                $warriorInfo['buy_price_max'],
                $warriorInfo['buy_price_max_date'],
            ];
        }
        return $filteredArray;
    }
}