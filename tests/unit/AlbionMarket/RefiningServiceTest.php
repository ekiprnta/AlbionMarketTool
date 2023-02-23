<?php

namespace unit\AlbionMarket;

use MZierdt\Albion\AlbionMarket\RefiningService;
use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class RefiningServiceTest extends TestCase
{
    use ProphecyTrait;

    private RefiningService $refiningService;

    protected function setUp(): void
    {
        $this->refiningService = new RefiningService();
    }

    /**
     * @dataProvider tierList
     */
    public function testCalculateAmountRawResource(int $tier, int $expectedResult): void
    {
        $this->assertEquals($expectedResult, $this->refiningService->calculateAmountRawResource($tier));
    }

    public function tierList(): array
    {
        return [[20, 0], [30, 2], [41, 2], [43, 2], [50, 3], [56, 3], [60, 4], [70, 5], [80, 5]];
    }

    /**
     * @dataProvider getResource
     */
    public function testCalculateResource(string $tier, string $expectedTier): void
    {
        /** @var ResourceEntity|ObjectProphecy $expectedResourceEntity */
        $expectedResourceEntity = $this->prophesize(ResourceEntity::class);
        $expectedResourceEntity->getTier()
            ->willReturn($expectedTier);

        $this->assertEquals(
            $expectedResourceEntity->reveal(),
            $this->refiningService->calculateResource($tier, [$expectedResourceEntity->reveal()])
        );
    }

    public function getResource(): array
    {
        return [['3', '3'], ['4', '4'], ['52', '52'], ['83', '83']];
    }

    /**
     * @dataProvider getResourceNull
     */
    public function testCalculateResourceNull(string $tier, string $expectedTier): void
    {
        $this->expectException(\InvalidArgumentException::class);
        /** @var ResourceEntity|ObjectProphecy $expectedResourceEntity */
        $expectedResourceEntity = $this->prophesize(ResourceEntity::class);
        $expectedResourceEntity->getTier()
            ->willReturn($expectedTier);

        $this->refiningService->calculateResource($tier, [$expectedResourceEntity->reveal()]);
    }

    public function getResourceNull(): array
    {
        return [['3', '4'], ['4', '41'], ['52', '53'], ['83', '8']];
    }

    /**
     * @dataProvider getLowerResource
     */
    public function testCalculateLowerResourceTier(string $tier, string $expectedTier): void
    {
        $this->assertEquals($expectedTier, $this->refiningService->calculateLowerResourceTier($tier));
    }

    public function getLowerResource(): array
    {
        return [['40', '30'], ['41', '30'], ['42', '30'], ['50', '40'], ['53', '43']];
    }

    /**
     * @dataProvider provideResourceCost
     */
    public function testCalculateResourceCost(
        float $result,
        int $rawResourcePrice,
        int $lowerResourcePrice,
        int $amountRawResource,
        float $percentage
    ): void {
        $this->assertEquals(
            $result,
            $this->refiningService->calculateResourceCost(
                $rawResourcePrice,
                $lowerResourcePrice,
                $amountRawResource,
                $percentage
            )
        );
    }

    public function provideResourceCost(): array
    {
        return [[138.3, 50, 100, 4, 53.9], [255, 50, 100, 4, 15]];
    }

    /**
     * @dataProvider getProfit
     */
    public function testCalculateProfit(
        float $expectedProfit,
        int $refinedResourcePrice,
        float $resourceCost
    ): void {
        $this->assertEqualsWithDelta(
            $expectedProfit,
            $this->refiningService->calculateProfit($refinedResourcePrice, $resourceCost),
            0.00000001
        );
    }

    public function getProfit(): array
    {
        return [[-150, 10000, 9500], [4750, 50000, 42000], [83.5, 100, 10]];
    }

    /**
     * @dataProvider getRefiningAmount
     */
    public function testCalculateRefiningAmount(string $tier, int $expectedAmount): void
    {
        $this->assertEquals($expectedAmount, $this->refiningService->calculateRefiningAmount($tier));
    }

    public function getRefiningAmount(): array
    {
        return [
            ['30', 968],
            ['40', 10000],
            ['41', 5000],
            ['42', 3333],
            ['43', 2000],
            ['50', 5000],
            ['51', 3000],
            ['52', 1765],
            ['53', 1035],
            ['60', 3000],
            ['61', 1667],
            ['62', 1000],
            ['63', 566],
            ['70', 1667],
            ['71', 968],
            ['72', 556],
            ['73', 319],
            ['80', 968],
            ['81', 545],
            ['82', 316],
            ['83', 180],
        ];
    }

    /**
     * @dataProvider getTotalProfit
     */
    public function testCalculateTotalProfit(int $amount, float $singleProfit, float $expectedProfit): void
    {
        $this->assertEquals($expectedProfit, $this->refiningService->calculateTotalProfit($amount, $singleProfit));
    }

    public function getTotalProfit(): array
    {
        return [[5, 1250, 6250.0], [3, 36.67, 110.01], [25, 25, 625.0]];
    }

    public function testGetRefiningRates(): void
    {
        $this->assertEquals([
            'No City Bonus No Focus' => 15.2,
            'No City Bonus Focus' => 43.5,
            'City Bonus No Focus' => 36.7,
            'City Bonus Focus' => 53.9,
        ], $this->refiningService->getRefiningRates());
    }
}
