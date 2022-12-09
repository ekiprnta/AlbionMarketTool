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
        $this->expectException('RuntimeException');
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
    public function testCalculateAmount(int $weight, float $itemWeight, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmtHelper->calculateAmount($weight, $itemWeight),
            0.0000001
        );
    }

    public function getAmountDat(): array
    {
        return [[2700, 15, 180], [2000, 6.3, 317], [2700, 14.7, 183], [25000, 12, 2083]];
    }
}
