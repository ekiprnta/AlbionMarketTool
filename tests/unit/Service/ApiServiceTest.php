<?php

namespace unit\Service;

use DG\BypassFinals;
use MZierdt\Albion\HttpClient;
use MZierdt\Albion\Service\ApiService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ApiServiceTest extends TestCase
{
    use ProphecyTrait;


    private ApiService $apiService;
    private ObjectProphecy|HttpClient $httpClient;

    protected function setUp(): void
    {
        BypassFinals::enable();
        $this->httpClient = $this->prophesize(HttpClient::class);

        $this->apiService = new ApiService($this->httpClient->reveal());
    }

    public function testGetJournals(): void
    {
        $apiUrl = 'https://www.albion-online-data.com/api/v2/stats/prices/T2_Journal_Warrior_FULL,T3_Journal_Warrior_FULL,T4_Journal_Warrior_FULL,T5_Journal_Warrior_FULL,T6_Journal_Warrior_FULL,T7_Journal_Warrior_FULL,T8_Journal_Warrior_FULL,T2_Journal_Warrior_EMPTY,T3_Journal_Warrior_EMPTY,T4_Journal_Warrior_EMPTY,T5_Journal_Warrior_EMPTY,T6_Journal_Warrior_EMPTY,T7_Journal_Warrior_EMPTY,T8_Journal_Warrior_EMPTY';
        $parameters = ['locations' => ApiService::CITY_ALL];
        $this->httpClient->get($apiUrl, $parameters)->willReturn('{"a": "b"}');

        $this->assertEquals(['a' => 'b'], $this->apiService->getJournals('Journal_Warrior'));
    }

    public function testGetResources(): void
    {
        $apiUrl = 'https://www.albion-online-data.com/api/v2/stats/prices/T2_metalBar,T3_metalBar,T4_metalBar,T5_metalBar,T6_metalBar,T7_metalBar,T8_metalBar,T4_metalBar_level1@1,T5_metalBar_level1@1,T6_metalBar_level1@1,T7_metalBar_level1@1,T8_metalBar_level1@1,T4_metalBar_level2@2,T5_metalBar_level2@2,T6_metalBar_level2@2,T7_metalBar_level2@2,T8_metalBar_level2@2,T4_metalBar_level3@3,T5_metalBar_level3@3,T6_metalBar_level3@3,T7_metalBar_level3@3,T8_metalBar_level3@3';
        $parameters = ['locations' => ApiService::CITY_ALL];
        $this->httpClient->get($apiUrl, $parameters)->willReturn('{"a": "b"}');

        $this->assertEquals(['a' => 'b'], $this->apiService->getResources('metalBar'));
    }

    public function testGetItems(): void
    {
        $apiUrl = 'https://www.albion-online-data.com/api/v2/stats/prices/T2_HEAD_PLATE_SET1,T3_HEAD_PLATE_SET1,T4_HEAD_PLATE_SET1,T5_HEAD_PLATE_SET1,T6_HEAD_PLATE_SET1,T7_HEAD_PLATE_SET1,T8_HEAD_PLATE_SET1,T4_HEAD_PLATE_SET1@1,T5_HEAD_PLATE_SET1@1,T6_HEAD_PLATE_SET1@1,T7_HEAD_PLATE_SET1@1,T8_HEAD_PLATE_SET1@1,T4_HEAD_PLATE_SET1@2,T5_HEAD_PLATE_SET1@2,T6_HEAD_PLATE_SET1@2,T7_HEAD_PLATE_SET1@2,T8_HEAD_PLATE_SET1@2,T4_HEAD_PLATE_SET1@3,T5_HEAD_PLATE_SET1@3,T6_HEAD_PLATE_SET1@3,T7_HEAD_PLATE_SET1@3,T8_HEAD_PLATE_SET1@3';
        $parameters = ['locations' => ApiService::CITY_ALL, 'qualities' => ApiService::QUALITY_GOOD];
        $this->httpClient->get($apiUrl, $parameters)->willReturn('{"a": "b"}');

        $this->assertEquals(['a' => 'b'], $this->apiService->getItems('HEAD_PLATE_SET1'));
    }
}