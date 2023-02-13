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

    protected function setUp(): void
    {
        $this->httpClient = $this->prophesize(HttpClient::class);

        $this->materialsApiService = new MaterialsApiService($this->httpClient->reveal());
    }
}