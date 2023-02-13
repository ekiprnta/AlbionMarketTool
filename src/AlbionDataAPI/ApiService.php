<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionDataAPI;

use MZierdt\Albion\HttpClient;

class ApiService // Buy Order ist buy_price_max
{
    protected const BASE_URL = 'https://www.albion-online-data.com/api/v2/stats/prices/';

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

    public function apiUrlAssembler(string $replacement, string $stringWithPlaceholders): string
    {
        return self::BASE_URL . str_replace('%s', $replacement, $stringWithPlaceholders);
    }

    private function jsonDecode(string $json)
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
}
