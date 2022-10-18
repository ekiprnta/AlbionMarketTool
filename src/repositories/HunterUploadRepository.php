<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use League\Csv\Writer;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\NameDataService;

class HunterUploadRepository implements UploadInterface
{
    private const PATH_TO_CSV = __DIR__ . '/../../assets/hunter.csv';

    public function __construct(
        private ApiService $apiService
    ) {
    }

    public function uploadIntoCsv()
    {
        $this->emptyCsv();

        $csv = $this->getCsvConnection();

        $this->insertIntoCsv(ApiService::ITEM_HUNTER_HELMET, $csv);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_ARMOR, $csv);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_BOOTS, $csv);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_BOW, $csv);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_SPEAR, $csv);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_NATURE_STAFF, $csv);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_DAGGER, $csv);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_QUARTERSTAFF, $csv);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_TORCH, $csv);
    }

    private function insertIntoCsv(string $itemCategory, Writer $csv): void
    {
        $itemArray = $this->apiService->getBlackMarketItem($itemCategory);
        $filteredItemArray = $this->filterArrays($itemArray, $itemCategory);
        $csv->insertAll($filteredItemArray);
    }

    private function getCsvConnection(): Writer
    {
        return Writer::createFromPath(self::PATH_TO_CSV, 'ab');
    }

    private function emptyCsv(): void
    {
        $header = [
            'itemId',
            'city',
            'quality',
            'sellOrderPrice',
            'sellOrderPriceDate',
            'buyOrderPrice',
            'buyOrderPriceDate',
            'primaryResource',
            'primaryResourceAmount',
            'secondaryResource',
            'secondaryResourceAmount',
        ];
        $csv = Writer::createFromPath(self::PATH_TO_CSV, 'wb');
        $csv->insertOne($header);
    }

    private function filterArrays(array $data, string $category): array
    {
        $nameData = NameDataService::getNameDataArray();
        $filteredArray = [];
        foreach ($data as $itemCategory) {
            foreach ($itemCategory as $item) {
                $itemWithoutTier = NameDataService::getFilteredArray($item['item_id']);

                $primaryResource = null;
                $primaryResourceAmount = null;
                $secondaryResource = null;
                $secondaryResourceAmount = null;
                foreach ($nameData['hunter'][$category] as $singleItem) {
                    if (strcasecmp($singleItem['id_snippet'], $itemWithoutTier) === 0) {
                        $primaryResource = $singleItem['primaryResource'];
                        $primaryResourceAmount = $singleItem['primaryResourceAmount'];
                        $secondaryResource = $singleItem['secondaryResource'];
                        $secondaryResourceAmount = $singleItem['secondaryResourceAmount'];
                    }
                }

                $filteredArray[] = [
                    $item['item_id'],
                    $item['city'],
                    $item['quality'],
                    $item['sell_price_min'],
                    $item['sell_price_min_date'],
                    $item['buy_price_max'],
                    $item['buy_price_max_date'],
                    $primaryResource,
                    $primaryResourceAmount,
                    $secondaryResource,
                    $secondaryResourceAmount
                ];
            }
        }
        return $filteredArray;
    }
}