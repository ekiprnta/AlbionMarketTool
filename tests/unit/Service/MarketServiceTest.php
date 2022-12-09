<?php

namespace unit\Service;

use MZierdt\Albion\Service\Market;
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
        return [[-123.23, 'D'], [123, 'C'], [250.876, 'C'], [678.12, 'B'], [900, 'A'], [1400, 'A'], [3467.09, 'S']];
    }

    /**
     * @dataProvider getProfitAndWeight
     */
    public function testCalculateWeightProfitQuotient(float $testProfit, int $testWeight, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->market->calculateWeightProfitQuotient($testProfit, $testWeight),
            0,
            000000001
        );
    }

    public function getProfitAndWeight(): array
    {
        return [
            [125000, 50, 2500],
            [125000, 2000, 62.5],
            [18_203_859, 2700, 6742.17],
            [9_664_554, 6900, 1400.66],
            [125000, 25000, 5],
        ];
    }
}
