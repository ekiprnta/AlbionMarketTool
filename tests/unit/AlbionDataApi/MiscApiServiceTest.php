<?php

declare(strict_types=1);

namespace unit\AlbionDataApi;

use MZierdt\Albion\AlbionDataAPI\ApiService;
use MZierdt\Albion\AlbionDataAPI\MiscApiService;
use MZierdt\Albion\HttpClient;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class MiscApiServiceTest extends TestCase
{
    use ProphecyTrait;

    private MiscApiService $miscApiService;
    private HttpClient|ObjectProphecy $httpClient;

    public function testGetJournals(): void
    {
        $apiUrl = 'https://www.albion-online-data.com/api/v2/stats/prices/T2_Journal_Warrior_FULL,T3_Journal_Warrior_FULL,T4_Journal_Warrior_FULL,T5_Journal_Warrior_FULL,T6_Journal_Warrior_FULL,T7_Journal_Warrior_FULL,T8_Journal_Warrior_FULL,T2_Journal_Warrior_EMPTY,T3_Journal_Warrior_EMPTY,T4_Journal_Warrior_EMPTY,T5_Journal_Warrior_EMPTY,T6_Journal_Warrior_EMPTY,T7_Journal_Warrior_EMPTY,T8_Journal_Warrior_EMPTY';
        $parameters = ['locations' => ApiService::CITY_ALL];
        $this->httpClient->get($apiUrl, $parameters)
            ->willReturn('{"a": "b"}');

        $this->assertEquals(['a' => 'b'], $this->miscApiService->getJournals('Journal_Warrior'));
    }

    public function testGetGoldPrice(): void
    {
        $apiUrl = 'https://www.albion-online-data.com/api/v2/stats/gold/';
        $parameters = ['count' => 1];
        $this->httpClient->get($apiUrl, $parameters)
            ->willReturn('[{"price": 12345}]');

        $this->assertEquals(12345, $this->miscApiService->getGoldPrice());
    }

    protected function setUp(): void
    {
        $this->httpClient = $this->prophesize(HttpClient::class);

        $this->miscApiService = new MiscApiService($this->httpClient->reveal());
    }
}