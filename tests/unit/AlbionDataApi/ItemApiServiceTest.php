<?php

declare(strict_types=1);

namespace unit\AlbionDataApi;

use MZierdt\Albion\AlbionDataAPI\ApiService;
use MZierdt\Albion\AlbionDataAPI\ItemApiService;
use MZierdt\Albion\HttpClient;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ItemApiServiceTest extends TestCase
{
    use ProphecyTrait;

    private ItemApiService $itemApiService;
    private HttpClient|ObjectProphecy $httpClient;

    public function testGetItems(): void
    {
        $apiUrl = 'https://www.albion-online-data.com/api/v2/stats/prices/T2_HEAD_PLATE_SET1,T3_HEAD_PLATE_SET1,T4_HEAD_PLATE_SET1,T5_HEAD_PLATE_SET1,T6_HEAD_PLATE_SET1,T7_HEAD_PLATE_SET1,T8_HEAD_PLATE_SET1,T4_HEAD_PLATE_SET1@1,T5_HEAD_PLATE_SET1@1,T6_HEAD_PLATE_SET1@1,T7_HEAD_PLATE_SET1@1,T8_HEAD_PLATE_SET1@1,T4_HEAD_PLATE_SET1@2,T5_HEAD_PLATE_SET1@2,T6_HEAD_PLATE_SET1@2,T7_HEAD_PLATE_SET1@2,T8_HEAD_PLATE_SET1@2,T4_HEAD_PLATE_SET1@3,T5_HEAD_PLATE_SET1@3,T6_HEAD_PLATE_SET1@3,T7_HEAD_PLATE_SET1@3,T8_HEAD_PLATE_SET1@3,T4_HEAD_PLATE_SET1@4,T5_HEAD_PLATE_SET1@4,T6_HEAD_PLATE_SET1@4,T7_HEAD_PLATE_SET1@4,T8_HEAD_PLATE_SET1@4';
        $parameters = [
            'locations' => ApiService::CITY_ALL,
            'qualities' => ApiService::QUALITY_GOOD,
        ];
        $this->httpClient->get($apiUrl, $parameters)
            ->willReturn('{"a": "b"}');

        $this->assertEquals(['a' => 'b'], $this->itemApiService->getItems('HEAD_PLATE_SET1'));
    }

    protected function setUp(): void
    {
        $this->httpClient = $this->prophesize(HttpClient::class);

        $this->itemApiService = new ItemApiService($this->httpClient->reveal());
    }
}
