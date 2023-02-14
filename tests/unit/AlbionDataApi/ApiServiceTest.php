<?php

namespace unit\AlbionDataApi;

use DG\BypassFinals;
use MZierdt\Albion\AlbionDataAPI\ApiService;
use MZierdt\Albion\HttpClient;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ApiServiceTest extends TestCase
{
    use ProphecyTrait;

    private ApiService $apiService;
    private ObjectProphecy|HttpClient $httpClient;

    public function testApiUrlAssembler(): void
    {
        $this->assertEquals(
            'https://www.albion-online-data.com/api/v2/stats/prices/blatestbla',
            $this->apiService->apiUrlAssembler('test', 'bla%sbla')
        );
    }

    protected function setUp(): void
    {
        BypassFinals::enable();
        $this->httpClient = $this->prophesize(HttpClient::class);

        $this->apiService = new ApiService($this->httpClient->reveal());
    }
}
