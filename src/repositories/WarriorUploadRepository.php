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

        $helmetArray = $this->apiService->getBlackMarketItem('plateHelmet');
        $armorArray = $this->apiService->getBlackMarketItem('plateArmor');
        $bootsArray = $this->apiService->getBlackMarketItem('plateBoots');
        $swordArray = $this->apiService->getBlackMarketItem('sword');
        $axeArray = $this->apiService->getBlackMarketItem('axe');
        $maceArray = $this->apiService->getBlackMarketItem('mace');
        $hammerArray = $this->apiService->getBlackMarketItem('hammer');
        $warGloveArray = $this->apiService->getBlackMarketItem('warGlove');
        $crossbowArray = $this->apiService->getBlackMarketItem('crossbow');
        $shieldArray = $this->apiService->getBlackMarketItem('shield');

        $warriorArray = array_merge(
            $helmetArray,
            $armorArray,
            $bootsArray,
            $swordArray,
            $axeArray,
            $maceArray,
            $hammerArray,
            $warGloveArray,
            $crossbowArray,
            $shieldArray,
        );

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