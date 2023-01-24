<?php

namespace unit\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Service\BlackMarketTransportingHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class BlackMarketTransportingHelperTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketTransportingHelper $bmtHelper;

    private ObjectProphecy|ItemEntity $bmItem;
    private ObjectProphecy|ItemEntity $cityItem;

    protected function setUp(): void
    {
        $this->bmtHelper = new BlackMarketTransportingHelper();
        $this->bmItem = $this->prophesize(ItemEntity::class);
        $this->cityItem = $this->prophesize(ItemEntity::class);
    }

    /**
     * @dataProvider getNameAndTierData
     */
    public function testCalculateCityItemException(
        string $cityTier,
        string $cityItem,
        string $bmTier,
        string $bmItem,
    ): void {
        $this->expectException(\RuntimeException::class);
        $this->cityItem->getTier()
            ->willReturn($cityTier);
        $this->cityItem->getName()
            ->willReturn($cityItem);
        $this->bmItem->getTier()
            ->willReturn($bmTier);
        $this->bmItem->getName()
            ->willReturn($bmItem);

        $this->bmtHelper->calculateCityItem($this->bmItem->reveal(), [$this->cityItem->reveal()]);
    }

    public function getNameAndTierData(): array
    {
        return [['b', 'b', 'a', 'a'], ['a', 'b', 'c', 'd'], ['a', 'b', 'a', 'c'], ['a', 'b', 'c', 'b']];
    }

    public function testCalculateCityItem(): void
    {
        $this->cityItem->getTier()
            ->willReturn('a');
        $this->cityItem->getName()
            ->willReturn('b');
        $this->bmItem->getTier()
            ->willReturn('a');
        $this->bmItem->getName()
            ->willReturn('b');

        $this->assertEquals(
            $this->cityItem->reveal(),
            $this->bmtHelper->calculateCityItem($this->bmItem->reveal(), [$this->cityItem->reveal()])
        );
    }

    /**
     * @dataProvider getSingleProfitDat
     */
    public function testCalculateSingleProfit(int $bmPrice, int $cityPrice, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmtHelper->calculateSingleProfit($bmPrice, $cityPrice),
            0.0000001
        );
    }

    public function getSingleProfitDat(): array
    {
        return [[1000, 1000, -65], [2310, 1000, 1159.85], [1000, 5445, -4510]];
    }

    /**
     * @dataProvider getProfitPercentageDat
     */
    public function testCalculateProfitPercentage(int $bmPrice, int $cityPrice, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmtHelper->calculateProfitPercentage($bmPrice, $cityPrice),
            0.0000001
        );
    }

    public function getProfitPercentageDat(): array
    {
        return [[1000, 999, 100.0], [2310, 999, 231.0], [1000, 5444, 18.365472910927455]];
    }

    /**
     * @dataProvider getTotalCostDat
     */
    public function testCalculateTotalCost(int $bmPrice, int $cityPrice, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmtHelper->calculateTotalCost($bmPrice, $cityPrice),
            0.0000001
        );
    }

    public function getTotalCostDat(): array
    {
        return [[1000, 1000, 1000000], [2310, 1000, 2310000], [1000, 5445, 5445000]];
    }

    /**
     * @dataProvider getProfitDat
     */
    public function testCalculateProfit(float $singleProfit, int $amount, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmtHelper->calculateProfit($singleProfit, $amount),
            0.0000001
        );
    }

    public function getProfitDat(): array
    {
        return [[-65, 12, -780], [1159.23, 85, 98534.55], [-4510, 88, -396880.0]];
    }

    /**
     * @dataProvider getAmountDat
     */
    public function testCalculateAmount(int $primAmount, int $secAmount, array $config, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmtHelper->calculateAmount($primAmount, $secAmount, $config),
            0.0000001
        );
    }

    public function getAmountDat(): array
    {
        return [
            [8, 8, ['16' => 20], 20],
            [12, 20, ['32' => 10990], 10990],
            [1, 99, ['100' => 2345], 2345],
        ];
    }
}
