<?php

declare(strict_types=1);

namespace unit\AlbionDataApi;

use MZierdt\Albion\AlbionDataAPI\ApiService;
use MZierdt\Albion\AlbionDataAPI\ResourceApiService;
use MZierdt\Albion\HttpClient;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ResourceApiServiceTest extends TestCase
{
    use ProphecyTrait;

    private ResourceApiService $resourceApiService;
    private HttpClient|ObjectProphecy $httpClient;

    public function testGetResources(): void
    {
        $apiUrl = 'https://www.albion-online-data.com/api/v2/stats/prices/T2_metalBar,T3_metalBar,T4_metalBar,T5_metalBar,T6_metalBar,T7_metalBar,T8_metalBar,T4_metalBar_level1@1,T5_metalBar_level1@1,T6_metalBar_level1@1,T7_metalBar_level1@1,T8_metalBar_level1@1,T4_metalBar_level2@2,T5_metalBar_level2@2,T6_metalBar_level2@2,T7_metalBar_level2@2,T8_metalBar_level2@2,T4_metalBar_level3@3,T5_metalBar_level3@3,T6_metalBar_level3@3,T7_metalBar_level3@3,T8_metalBar_level3@3,T4_metalBar_level4@4,T5_metalBar_level4@4,T6_metalBar_level4@4,T7_metalBar_level4@4,T8_metalBar_level4@4';
        $parameters = ['locations' => ApiService::CITY_ALL];
        $this->httpClient->get($apiUrl, $parameters)
            ->willReturn('{"a": "b"}');

        $this->assertEquals(['a' => 'b'], $this->resourceApiService->getResources('metalBar'));
    }

    protected function setUp(): void
    {
        $this->httpClient = $this->prophesize(HttpClient::class);

        $this->resourceApiService = new ResourceApiService($this->httpClient->reveal());
    }
}
