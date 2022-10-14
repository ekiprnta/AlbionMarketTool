<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\HttpClient;

class ApiService // Buy Order ist buy_price_max
{
    private const BASE_URL = 'https://www.albion-online-data.com/api/v2/stats/prices/';
    private const BASE_PLANKS = 'T2_Planks,T3_Planks,T4_Planks,T5_Planks,T6_Planks,T7_Planks,T8_Planks,T4_Planks_level1@1,T5_Planks_level1@1,T6_Planks_level1@1,T7_Planks_level1@1,T8_Planks_level1@1,T4_Planks_level2@2,T5_Planks_level2@2,T6_Planks_level2@2,T7_Planks_level2@2,T8_Planks_level2@2,T4_Planks_level3@3,T5_Planks_level3@3,T6_Planks_level3@3,T7_Planks_level3@3,T8_Planks_level3@3';
    private const BASE_STONEBLOCK = 'T2_StoneBlock,T3_StoneBlock,T4_StoneBlock,T5_StoneBlock,T6_StoneBlock,T7_StoneBlock,T8_StoneBlock';
    private const BASE_METALBAR = 'T2_MetalBar,T3_MetalBar,T4_MetalBar,T5_MetalBar,T6_MetalBar,T7_MetalBar,T8_MetalBar,T4_MetalBar_level1@1,T5_MetalBar_level1@1,T6_MetalBar_level1@1,T7_MetalBar_level1@1,T8_MetalBar_level1@1,T4_MetalBar_level2@2,T5_MetalBar_level2@2,T6_MetalBar_level2@2,T7_MetalBar_level2@2,T8_MetalBar_level2@2,T4_MetalBar_level3@3,T5_MetalBar_level3@3,T6_MetalBar_level3@3,T7_MetalBar_level3@3,T8_MetalBar_level3@3';
    private const BASE_LEATHER = 'T2_Leather,T3_Leather,T4_Leather,T5_Leather,T6_Leather,T7_Leather,T8_Leather,T4_Leather_level1@1,T5_Leather_level1@1,T6_Leather_level1@1,T7_Leather_level1@1,T8_Leather_level1@1,T4_Leather_level2@2,T5_Leather_level2@2,T6_Leather_level2@2,T7_Leather_level2@2,T8_Leather_level2@2,T4_Leather_level3@3,T5_Leather_level3@3,T6_Leather_level3@3,T7_Leather_level3@3,T8_Leather_level3@3';
    private const BASE_CLOTH = 'T2_Cloth,T3_Cloth,T4_Cloth,T5_Cloth,T6_Cloth,T7_Cloth,T8_Cloth,T4_Cloth_level1@1,T5_Cloth_level1@1,T6_Cloth_level1@1,T7_Cloth_level1@1,T8_Cloth_level1@1,T4_Cloth_level2@2,T5_Cloth_level2@2,T6_Cloth_level2@2,T7_Cloth_level2@2,T8_Cloth_level2@2,T4_Cloth_level3@3,T5_Cloth_level3@3,T6_Cloth_level3@3,T7_Cloth_level3@3,T8_Cloth_level3@3';

    private const CITY_LYMHURST = 'Lymhurst';
    private const CITY_FORTSTERLING = 'FortSterling';
    private const CITY_BRIDGEWATCH = 'Bridgewatch';
    private const CITY_MARTLOCK = 'Martlock';
    private const CITY_THETFORD = 'Thetford';
    private const CITY_Caerleon = 'Caerleon';

    private const QUALITY_NORMAL = 1;
    private const QUALITY_GOOD = 2;
    private const QUALITY_OUTSTANDING = 3;
    private const QUALITY_EXCELLENT = 4;
    private const QUALITY_MASTERPIECE = 5;

    public function __construct(private HttpClient $httpClient)
    {
    }

    public function getResource(string $resourceType)
    {
        $baseURL = match ($resourceType) {
            'metalBar' => self::BASE_URL . self::BASE_METALBAR,
            'planks' => self::BASE_URL . self::BASE_PLANKS,
            'cloth' => self::BASE_URL . self::BASE_CLOTH,
            'leather' => self::BASE_URL . self::BASE_LEATHER,
        };

        return $this->httpClient->get($baseURL, [
            'location' => ['Lymhurst,Caerleon'],
        ]);
    }
}
