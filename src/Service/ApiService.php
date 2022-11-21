<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\HttpClient;

class ApiService // Buy Order ist buy_price_max
{
    private const BASE_URL = 'https://www.albion-online-data.com/api/v2/stats/prices/';
    private const RESOURCE_TIERS_WITH_PLACEHOLDER = 'T2_%s,T3_%s,T4_%s,T5_%s,T6_%s,T7_%s,T8_%s,T4_%s_level1@1,T5_%s_level1@1,T6_%s_level1@1,T7_%s_level1@1,T8_%s_level1@1,T4_%s_level2@2,T5_%s_level2@2,T6_%s_level2@2,T7_%s_level2@2,T8_%s_level2@2,T4_%s_level3@3,T5_%s_level3@3,T6_%s_level3@3,T7_%s_level3@3,T8_%s_level3@3';
    public const ITEM_TIERS_WITH_PLACEHOLDER = 'T2_%s,T3_%s,T4_%s,T5_%s,T6_%s,T7_%s,T8_%s,T4_%s@1,T5_%s@1,T6_%s@1,T7_%s@1,T8_%s@1,T4_%s@2,T5_%s@2,T6_%s@2,T7_%s@2,T8_%s@2,T4_%s@3,T5_%s@3,T6_%s@3,T7_%s@3,T8_%s@3';
    public const JOURNAL_TIERS_WITH_PLACEHOLDER = 'T2_%s_FULL,T3_%s_FULL,T4_%s_FULL,T5_%s_FULL,T6_%s_FULL,T7_%s_FULL,T8_%s_FULL,T2_%s_EMPTY,T3_%s_EMPTY,T4_%s_EMPTY,T5_%s_EMPTY,T6_%s_EMPTY,T7_%s_EMPTY,T8_%s_EMPTY';

    private const RESOURCE_PLANKS = 'Planks';
    private const RESOURCE_METALBAR = 'MetalBar';
    private const RESOURCE_LEATHER = 'Leather';
    private const RESOURCE_CLOTH = 'Cloth';

    public const CITY_LYMHURST = 'Lymhurst';
    public const CITY_FORTSTERLING = 'FortSterling';
    public const CITY_BRIDGEWATCH = 'Bridgewatch';
    public const CITY_MARTLOCK = 'Martlock';
    public const CITY_THETFORD = 'Thetford';
    public const CITY_BLACKMARKET = 'BlackMarket';

    public const CITY_ALL =
        self::CITY_BRIDGEWATCH . ',' .
        self::CITY_FORTSTERLING . ',' .
        self::CITY_LYMHURST . ',' .
        self::CITY_MARTLOCK . ',' .
        self::CITY_THETFORD . ',' .
        self::CITY_BLACKMARKET . ',';

    private const QUALITY_GOOD = 2;

    public function __construct(
        private HttpClient $httpClient
    ) {
    }


    public function getJournals(string $journalType)
    {
        $apiUrl = $this->apiUrlAssembler($journalType, self::JOURNAL_TIERS_WITH_PLACEHOLDER);


        return $this->jsonDecode($this->httpClient->get($apiUrl, ['locations' => self::CITY_ALL]));
    }

    public function getResource(string $resourceType)
    {
        $apiUrl = match ($resourceType) {
            'metalBar' => $this->apiUrlAssembler(self::RESOURCE_METALBAR),
            'planks' => $this->apiUrlAssembler(self::RESOURCE_PLANKS),
            'cloth' => $this->apiUrlAssembler(self::RESOURCE_CLOTH),
            'leather' => $this->apiUrlAssembler(self::RESOURCE_LEATHER),
            default => throw new \InvalidArgumentException('wrong Resource Type in ApiService')
        };

        return $this->jsonDecode($this->httpClient->get($apiUrl, ['locations' => self::CITY_ALL]));
    }


    private function apiUrlAssembler(
        string $replacement,
        string $stringWithPlaceholders = self::RESOURCE_TIERS_WITH_PLACEHOLDER
    ): string|array {
        $completeUrl = self::BASE_URL;
        if ($stringWithPlaceholders === self::RESOURCE_TIERS_WITH_PLACEHOLDER ||
            $stringWithPlaceholders === self::JOURNAL_TIERS_WITH_PLACEHOLDER) {
            return $completeUrl . str_replace('%s', $replacement, $stringWithPlaceholders);
        }
        $nameData = NameDataService::getNameDataArray();
        $urlArray = [];
        foreach ($nameData as $itemCategory) {
            if (array_key_exists($replacement, $itemCategory)) {
                foreach ($itemCategory[$replacement] as $item) {
                    $urlArray[] = $completeUrl . str_replace('%s', $item['id_snippet'], $stringWithPlaceholders);
                }
            }
        }
        return $urlArray;
    }

    private function jsonDecode(string $json)
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }


    public function getItems(string $itemName): array
    {
        $apiUrl = $this->apiUrlAssembler($itemName, self::ITEM_TIERS_WITH_PLACEHOLDER);

        if (is_array($apiUrl)) {
            $apiData = [];
            foreach ($apiUrl as $url) {
                $jsonFromArray = $this->httpClient->get(
                    $url,
                    [
                        'locations' => self::CITY_ALL,
                        'qualities' => self::QUALITY_GOOD,
                    ]
                );
                $apiData[] = $this->jsonDecode($jsonFromArray);
            }
            return $apiData;
        }

        $json = $this->httpClient->get($apiUrl, [
            'locations' => $cities,
            'qualities' => self::QUALITY_GOOD,
        ]);
        return $this->jsonDecode($json);
    }
}
