<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionDataAPI;

use MZierdt\Albion\HttpClient;

class ApiService // Buy Order ist buy_price_max
{
    protected const BASE_URL = 'https://www.albion-online-data.com/api/v2/stats/prices/';
    private const URL_GOLD = 'https://www.albion-online-data.com/api/v2/stats/gold/';
    private const RESOURCE_TIERS_WITH_PLACEHOLDER = 'T2_%s,T3_%s,T4_%s,T5_%s,T6_%s,T7_%s,T8_%s,T4_%s_level1@1,T5_%s_level1@1,T6_%s_level1@1,T7_%s_level1@1,T8_%s_level1@1,T4_%s_level2@2,T5_%s_level2@2,T6_%s_level2@2,T7_%s_level2@2,T8_%s_level2@2,T4_%s_level3@3,T5_%s_level3@3,T6_%s_level3@3,T7_%s_level3@3,T8_%s_level3@3,T4_%s_level4@4,T5_%s_level4@4,T6_%s_level4@4,T7_%s_level4@4,T8_%s_level4@4';
    final public const ITEM_TIERS_WITH_PLACEHOLDER = 'T2_%s,T3_%s,T4_%s,T5_%s,T6_%s,T7_%s,T8_%s,T4_%s@1,T5_%s@1,T6_%s@1,T7_%s@1,T8_%s@1,T4_%s@2,T5_%s@2,T6_%s@2,T7_%s@2,T8_%s@2,T4_%s@3,T5_%s@3,T6_%s@3,T7_%s@3,T8_%s@3,T4_%s@4,T5_%s@4,T6_%s@4,T7_%s@4,T8_%s@4';
    final public const JOURNAL_TIERS_WITH_PLACEHOLDER = 'T2_%s_FULL,T3_%s_FULL,T4_%s_FULL,T5_%s_FULL,T6_%s_FULL,T7_%s_FULL,T8_%s_FULL,T2_%s_EMPTY,T3_%s_EMPTY,T4_%s_EMPTY,T5_%s_EMPTY,T6_%s_EMPTY,T7_%s_EMPTY,T8_%s_EMPTY';

    final public const RESOURCE_PLANKS = 'Planks';
    final public const RESOURCE_METALBAR = 'MetalBar';
    final public const RESOURCE_LEATHER = 'Leather';
    final public const RESOURCE_CLOTH = 'Cloth';

    final public const CITY_LYMHURST = 'Lymhurst';
    final public const CITY_FORTSTERLING = 'FortSterling';
    final public const CITY_BRIDGEWATCH = 'Bridgewatch';
    final public const CITY_MARTLOCK = 'Martlock';
    final public const CITY_THETFORD = 'Thetford';
    final public const CITY_BLACKMARKET = 'BlackMarket';

    final public const CITY_ALL =
        self::CITY_BRIDGEWATCH . ',' .
        self::CITY_FORTSTERLING . ',' .
        self::CITY_LYMHURST . ',' .
        self::CITY_MARTLOCK . ',' .
        self::CITY_THETFORD . ',' .
        self::CITY_BLACKMARKET . ',';

    final public const QUALITY_GOOD = 2;

    public function __construct(
        private readonly HttpClient $httpClient
    ) {
    }

    /**
     * @throws \JsonException
     */
    protected function get(string $apiUrl, array $params)
    {
        return $this->jsonDecode($this->httpClient->get($apiUrl, $params));
    }

    public function getMaterials()
    {
        $apiUrl = self::BASE_URL . self::MATERIAL_TIERS;
        return $this->jsonDecode($this->httpClient->get($apiUrl, ['locations' => self::CITY_ALL]));
    }

    public function getJournals(string $journalType)
    {
        $apiUrl = $this->apiUrlAssembler($journalType, self::JOURNAL_TIERS_WITH_PLACEHOLDER);
        return $this->jsonDecode($this->httpClient->get($apiUrl, ['locations' => self::CITY_ALL]));
    }

    public function getResources(string $resourceType)
    {
        $apiUrl = $this->apiUrlAssembler($resourceType, self::RESOURCE_TIERS_WITH_PLACEHOLDER);
        $json = $this->httpClient->get($apiUrl, ['locations' => self::CITY_ALL]);
        return $this->jsonDecode($json);
    }

    public function getItems(string $itemName): array
    {
        $apiUrl = $this->apiUrlAssembler($itemName, self::ITEM_TIERS_WITH_PLACEHOLDER);

        $json = $this->httpClient->get($apiUrl, [
            'locations' => self::CITY_ALL,
            'qualities' => self::QUALITY_GOOD,
        ]);
        return $this->jsonDecode($json);
    }

    public function apiUrlAssembler(string $replacement, string $stringWithPlaceholders): string
    {
        return self::BASE_URL . str_replace('%s', $replacement, $stringWithPlaceholders);
    }

    private function jsonDecode(string $json)
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getGoldPrice(): int
    {
        $json = $this->httpClient->get(self::URL_GOLD, ['count' => 1]);
        $jsonDecode = $this->jsonDecode($json);
        return $jsonDecode[0]['price'];
    }
}
