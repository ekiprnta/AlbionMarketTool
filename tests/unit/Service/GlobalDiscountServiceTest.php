<?php

namespace unit\Service;

use MZierdt\Albion\AlbionDataAPI\MiscApiService;
use MZierdt\Albion\Service\GlobalDiscountService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class GlobalDiscountServiceTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider getGoldPriceDataProvider
     */
    public function testGetGlobalDiscount(float $result, int $goldPrice): void
    {
        /** @var MiscApiService|ObjectProphecy $miscApiService */
        $miscApiService = $this->prophesize(MiscApiService::class);

        $miscApiService->getGoldPrice()
            ->willReturn($goldPrice);
        $globalDiscountService = new GlobalDiscountService($miscApiService->reveal());

        $this->assertEqualsWithDelta($result, $globalDiscountService->getGlobalDiscount(), 0.0000001);
    }

    public function getGoldPriceDataProvider(): array
    {
        return [[0, 5000], [0.1, 4500], [-0.1, 5500], [0.02, 4900]];
    }
}
