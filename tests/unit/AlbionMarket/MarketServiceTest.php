<?php

namespace unit\AlbionMarket;

use MZierdt\Albion\AlbionMarket\Market;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class MarketServiceTest extends TestCase
{
    use ProphecyTrait;

    private Market $market;

    protected function setUp(): void
    {
        $this->market = new Market();
    }

    /**
     * @dataProvider getWeightProfitQuotient
     */
    public function testCalculateProfitGrade(float $testData, string $expectedResult): void
    {
        $this->assertEquals($expectedResult, $this->market->calculateProfitGrade($testData));
    }

    public function getWeightProfitQuotient(): array
    {
        return [
            [99, 'D'],
            [100, 'C'],
            [129, 'C'],
            [130, 'B'],
            [159, 'B'],
            [160, 'A'],
            [199, 'A'],
            [200, 'S'],
            [1000, 'S'],
            [80.84, 'D'],
        ];
    }

    /**
     * @dataProvider getProfitAndWeight
     */
    public function testCalculateProfitPercentage(float $turnover, float $totalCost, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->market->calculateProfitPercentage($turnover, $totalCost),
            0,
            000000001
        );
    }

    public function getProfitAndWeight(): array
    {
        return [[110000, 100000, 107.25], [20000, 25000, 78], [15, 10, 132.95]];
    }
}
