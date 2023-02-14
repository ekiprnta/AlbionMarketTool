<?php

declare(strict_types=1);

namespace unit\AlbionDataApi;

use MZierdt\Albion\AlbionDataAPI\ApiService;
use MZierdt\Albion\AlbionDataAPI\MaterialsApiService;
use MZierdt\Albion\HttpClient;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class MaterialApiServiceTest extends TestCase
{
    use ProphecyTrait;

    private MaterialsApiService $materialsApiService;
    private HttpClient|ObjectProphecy $httpClient;

    public function testGetMaterials(): void
    {
        $apiUrl = 'https://www.albion-online-data.com/api/v2/stats/prices/T4_rune,T4_soul,T4_relic,T4_shard_avalonian,T5_rune,T5_soul,T5_relic,T5_shard_avalonian,T6_rune,T6_soul,T6_relic,T6_shard_avalonian,T7_rune,T7_soul,T7_relic,T7_shard_avalonian,T8_rune,T8_soul,T8_relic,T8_shard_avalonian';
        $parameters = ['locations' => ApiService::CITY_ALL];
        $this->httpClient->get($apiUrl, $parameters)
            ->willReturn('{"a": "b"}');

        $this->assertEquals(['a' => 'b'], $this->materialsApiService->getMaterials());
    }

    public function testGetHearts(): void
    {
        $apiUrl = 'https://www.albion-online-data.com/api/v2/stats/prices/T1_FACTION_FOREST_TOKEN_1,T1_FACTION_HIGHLAND_TOKEN_1,T1_FACTION_STEPPE_TOKEN_1,T1_FACTION_MOUNTAIN_TOKEN_1,T1_FACTION_SWAMP_TOKEN_1,T1_FACTION_CAERLEON_TOKEN_1';
        $parameters = ['locations' => ApiService::CITY_ALL];
        $this->httpClient->get($apiUrl, $parameters)
            ->willReturn('{"a": "b"}');

        $this->assertEquals(['a' => 'b'], $this->materialsApiService->getHearts());
    }

    public function testGetCapeArtifacts(): void
    {
        $apiUrl = 'https://www.albion-online-data.com/api/v2/stats/prices/T4_CAPEITEM_FW_BRIDGEWATCH_BP,T5_CAPEITEM_FW_BRIDGEWATCH_BP,T6_CAPEITEM_FW_BRIDGEWATCH_BP,T7_CAPEITEM_FW_BRIDGEWATCH_BP,T8_CAPEITEM_FW_BRIDGEWATCH_BP,T4_CAPEITEM_FW_FORTSTERLING_BP,T5_CAPEITEM_FW_FORTSTERLING_BP,T6_CAPEITEM_FW_FORTSTERLING_BP,T7_CAPEITEM_FW_FORTSTERLING_BP,T8_CAPEITEM_FW_FORTSTERLING_BP,T4_CAPEITEM_FW_LYMHURST_BP,T5_CAPEITEM_FW_LYMHURST_BP,T6_CAPEITEM_FW_LYMHURST_BP,T7_CAPEITEM_FW_LYMHURST_BP,T8_CAPEITEM_FW_LYMHURST_BP,T4_CAPEITEM_FW_MARTLOCK_BP,T5_CAPEITEM_FW_MARTLOCK_BP,T6_CAPEITEM_FW_MARTLOCK_BP,T7_CAPEITEM_FW_MARTLOCK_BP,T8_CAPEITEM_FW_MARTLOCK_BP,T4_CAPEITEM_FW_THETFORD_BP,T5_CAPEITEM_FW_THETFORD_BP,T6_CAPEITEM_FW_THETFORD_BP,T7_CAPEITEM_FW_THETFORD_BP,T8_CAPEITEM_FW_THETFORD_BP,T4_CAPEITEM_FW_CAERLEON_BP,T5_CAPEITEM_FW_CAERLEON_BP,T6_CAPEITEM_FW_CAERLEON_BP,T7_CAPEITEM_FW_CAERLEON_BP,T8_CAPEITEM_FW_CAERLEON_BP,T4_CAPEITEM_HERETIC_BP,T5_CAPEITEM_HERETIC_BP,T6_CAPEITEM_HERETIC_BP,T7_CAPEITEM_HERETIC_BP,T8_CAPEITEM_HERETIC_BP,T4_CAPEITEM_UNDEAD_BP,T5_CAPEITEM_UNDEAD_BP,T6_CAPEITEM_UNDEAD_BP,T7_CAPEITEM_UNDEAD_BP,T8_CAPEITEM_UNDEAD_BP,T4_CAPEITEM_KEEPER_BP,T5_CAPEITEM_KEEPER_BP,T6_CAPEITEM_KEEPER_BP,T7_CAPEITEM_KEEPER_BP,T8_CAPEITEM_KEEPER_BP,T4_CAPEITEM_MORGANA_BP,T5_CAPEITEM_MORGANA_BP,T6_CAPEITEM_MORGANA_BP,T7_CAPEITEM_MORGANA_BP,T8_CAPEITEM_MORGANA_BP,T4_CAPEITEM_DEMON_BP,T5_CAPEITEM_DEMON_BP,T6_CAPEITEM_DEMON_BP,T7_CAPEITEM_DEMON_BP,T8_CAPEITEM_DEMON_BP';
        $parameters = ['locations' => ApiService::CITY_ALL];
        $this->httpClient->get($apiUrl, $parameters)
            ->willReturn('{"a": "b"}');

        $this->assertEquals(['a' => 'b'], $this->materialsApiService->getCapeArtifacts());
    }

    protected function setUp(): void
    {
        $this->httpClient = $this->prophesize(HttpClient::class);

        $this->materialsApiService = new MaterialsApiService($this->httpClient->reveal());
    }
}
