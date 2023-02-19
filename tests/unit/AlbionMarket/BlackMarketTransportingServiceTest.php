<?php

namespace unit\AlbionMarket;

use MZierdt\Albion\AlbionMarket\BlackMarketTransportingService;
use MZierdt\Albion\Entity\ItemEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BlackMarketTransportingServiceTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketTransportingService $bmtService;

    protected function setUp(): void
    {
        $this->bmtService = new BlackMarketTransportingService();
    }

    /**
     * @dataProvider getNameAndTierData
     */
    public function testCalculateCityItemException(
        int $cityTier,
        string $cityItem,
        int $bmTier,
        string $bmItem,
    ): void {
        $this->expectException(\RuntimeException::class);
        $cityItem = (new ItemEntity())->setTier($cityTier)->setName($cityItem);
        $bmItem = (new ItemEntity())->setTier($bmTier)->setName($bmItem);

        $this->bmtService->calculateCityItem($bmItem, [$cityItem]);
    }

    public function getNameAndTierData(): array
    {
        return [[1, 'b', 2, 'a'], [1, 'b', 3, 'd'], [1, 'b', 1, 'c'], [1, 'b', 3, 'b']];
    }

    public function testCalculateCityItem(): void
    {
        $cityItem = (new ItemEntity())->setTier(1)->setName('b');
        $bmItem = (new ItemEntity())->setTier(1)->setName('b');

        $this->assertEquals(
            $cityItem,
            $this->bmtService->calculateCityItem($bmItem, [$cityItem])
        );
    }

    /**
     * @dataProvider getProfitData
     */
    public function testCalculateProfit(int $bmPrice, int $cityPrice, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmtService->calculateProfit($bmPrice, $cityPrice),
            0.0000001
        );
    }

    public function getProfitData(): array
    {
        return [[1000, 1000, -65], [2310, 1000, 1159.85], [1000, 5445, -4510]];
    }

    /**
     * @dataProvider getAmountDat
     */
    public function testCalculateAmount(int $primAmount, int $secAmount, array $config, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmtService->calculateAmount($primAmount, $secAmount, $config),
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
