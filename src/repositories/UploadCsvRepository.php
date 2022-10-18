<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use League\Csv\Writer;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\NameDataService;

class UploadCsvRepository
{
    private const PATH_TO_CSV_WARRIOR = __DIR__ . '/../../assets/warrior.csv';
    private const PATH_TO_CSV_MAGE = __DIR__ . '/../../assets/mage.csv';
    private const PATH_TO_CSV_HUNTER = __DIR__ . '/../../assets/hunter.csv';
    private const WARRIOR = 'warrior';
    private const MAGE = 'mage';
    private const HUNTER = 'hunter';

    public function __construct(private ApiService $apiService)
    {
    }

    public function fillItemsCsvFiles()
    {
        $this->fillWarriorCsvFile();
        $this->fillMageCsvFile();
        $this->fillHunterCsvFile();
    }

    private function fillWarriorCsvFile()
    {
        $this->emptyCsv(self::PATH_TO_CSV_WARRIOR);

        $csv = $this->getCsvConnection(self::PATH_TO_CSV_WARRIOR);

        $this->insertIntoCsv(ApiService::ITEM_WARRIOR_HELMET, $csv, self::WARRIOR);
        $this->insertIntoCsv(ApiService::ITEM_WARRIOR_ARMOR, $csv, self::WARRIOR);
        $this->insertIntoCsv(ApiService::ITEM_WARRIOR_BOOTS, $csv, self::WARRIOR);
        $this->insertIntoCsv(ApiService::ITEM_WARRIOR_SWORD, $csv, self::WARRIOR);
        $this->insertIntoCsv(ApiService::ITEM_WARRIOR_AXE, $csv, self::WARRIOR);
        $this->insertIntoCsv(ApiService::ITEM_WARRIOR_MACE, $csv, self::WARRIOR);
        $this->insertIntoCsv(ApiService::ITEM_WARRIOR_HAMMER, $csv, self::WARRIOR);
        $this->insertIntoCsv(ApiService::ITEM_WARRIOR_WAR_GLOVE, $csv, self::WARRIOR);
        $this->insertIntoCsv(ApiService::ITEM_WARRIOR_CROSSBOW, $csv, self::WARRIOR);
        $this->insertIntoCsv(ApiService::ITEM_WARRIOR_SHIELD, $csv, self::WARRIOR);
    }

    private function fillMageCsvFile()
    {
        $this->emptyCsv(self::PATH_TO_CSV_MAGE);

        $csv = $this->getCsvConnection(self::PATH_TO_CSV_MAGE);

        $this->insertIntoCsv(ApiService::ITEM_MAGE_HELMET, $csv, self::MAGE);
        $this->insertIntoCsv(ApiService::ITEM_MAGE_ARMOR, $csv, self::MAGE);
        $this->insertIntoCsv(ApiService::ITEM_MAGE_BOOTS, $csv, self::MAGE);
        $this->insertIntoCsv(ApiService::ITEM_MAGE_FIRE_STAFF, $csv, self::MAGE);
        $this->insertIntoCsv(ApiService::ITEM_MAGE_HOLY_STAFF, $csv, self::MAGE);
        $this->insertIntoCsv(ApiService::ITEM_MAGE_ARCANE_STAFF, $csv, self::MAGE);
        $this->insertIntoCsv(ApiService::ITEM_MAGE_FROST_STAFF, $csv, self::MAGE);
        $this->insertIntoCsv(ApiService::ITEM_MAGE_CURSE_STAFF, $csv, self::MAGE);
        $this->insertIntoCsv(ApiService::ITEM_MAGE_TOME_STAFF, $csv, self::MAGE);
    }

    private function fillHunterCsvFile()
    {
        $this->emptyCsv(self::PATH_TO_CSV_HUNTER);

        $csv = $this->getCsvConnection(self::PATH_TO_CSV_HUNTER);

        $this->insertIntoCsv(ApiService::ITEM_HUNTER_HELMET, $csv, self::HUNTER);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_ARMOR, $csv, self::HUNTER);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_BOOTS, $csv, self::HUNTER);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_BOW, $csv, self::HUNTER);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_SPEAR, $csv, self::HUNTER);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_NATURE_STAFF, $csv, self::HUNTER);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_DAGGER, $csv, self::HUNTER);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_QUARTERSTAFF, $csv, self::HUNTER);
        $this->insertIntoCsv(ApiService::ITEM_HUNTER_TORCH, $csv, self::HUNTER);
    }

    private function emptyCsv(string $path): void
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
            'bonusCity',
        ];
        $csv = Writer::createFromPath($path, 'wb');
        $csv->insertOne($header);
    }

    private function getCsvConnection(string $path): Writer
    {
        return Writer::createFromPath($path, 'ab');
    }

    private function insertIntoCsv(string $itemCategory, Writer $csv, string $mainCategory): void
    {
        $itemArray = $this->apiService->getBlackMarketItem($itemCategory);
        $filteredItemArray = $this->filterArrays($itemArray, $itemCategory, $mainCategory);
        $csv->insertAll($filteredItemArray);
    }

    private function filterArrays(array $data, string $category, string $mainCategory): array
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
                $bonusCity = null;
                foreach ($nameData[$mainCategory][$category] as $singleItem) {
                    if (strcasecmp($singleItem['id_snippet'], $itemWithoutTier) === 0) {
                        $primaryResource = $singleItem['primaryResource'];
                        $primaryResourceAmount = $singleItem['primaryResourceAmount'];
                        $secondaryResource = $singleItem['secondaryResource'];
                        $secondaryResourceAmount = $singleItem['secondaryResourceAmount'];
                        $bonusCity = $singleItem['bonusCity'];
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
                    $secondaryResourceAmount,
                    $bonusCity,
                ];
            }
        }
        return $filteredArray;
    }
}
