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

        $helmetArray = $this->apiService->getBlackMarketItem('leatherHelmet');
        $armorArray = $this->apiService->getBlackMarketItem('leatherArmor');
        $bootsArray = $this->apiService->getBlackMarketItem('leatherBoots');
        $bowArray = $this->apiService->getBlackMarketItem('bow');
        $spearArray = $this->apiService->getBlackMarketItem('spear');
        $natureArray = $this->apiService->getBlackMarketItem('nature');
        $daggerArray = $this->apiService->getBlackMarketItem('dagger');
        $quarterstaffArray = $this->apiService->getBlackMarketItem('quarterstaff');
        $torchArray = $this->apiService->getBlackMarketItem('torch');

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