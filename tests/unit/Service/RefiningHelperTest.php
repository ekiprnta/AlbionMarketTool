<?php

namespace unit\Service;

use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\Service\RefiningHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class RefiningHelperTest extends TestCase
{
    use ProphecyTrait;

    private RefiningHelper $refiningHelper;

    protected function setUp(): void
    {
        $this->refiningHelper = new RefiningHelper();
    }

    /**
     * @dataProvider tierList
     */
    public function testCalculateAmountRawResource(string $tier, int $expectedResult): void
    {
        $this->assertEquals($expectedResult, $this->refiningHelper->calculateAmountRawResource($tier));
    }

    public function tierList(): array
    {
        return [['2', 0], ['3', 2], ['41', 2], ['43', 2], ['5', 3], ['56', 3], ['6', 4], ['7', 5], ['8', 5]];
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
            $this->refiningHelper->calculateResource($tier, [$expectedResourceEntity->reveal()])
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

        $this->refiningHelper->calculateResource($tier, [$expectedResourceEntity->reveal()]);
    }

    public function getResourceNull(): array
    {
        return [['3', '4'], ['4', '41'], ['52', '53'], ['83', '8']];
    }

    /**
     * @dataProvider getLowerResource
     */
    public function testCalculateLowerResource(string $tier, string $expectedTier): void
    {
        /** @var ResourceEntity|ObjectProphecy $expectedResourceEntity */
        $expectedResourceEntity = $this->prophesize(ResourceEntity::class);
        $expectedResourceEntity->getTier()
            ->willReturn($expectedTier);

        $this->assertEquals(
            $expectedResourceEntity->reveal(),
            $this->refiningHelper->calculateResource($tier, [$expectedResourceEntity->reveal()])
        );
    }

    public function getLowerResource(): array
    {
        return [['3', '3'], ['4', '4'], ['52', '52'], ['83', '83']];
    }

    /**
     * @dataProvider getLowerResourceNull
     */
    public function testCalculateLowerResourceNull(string $tier, string $expectedTier): void
    {
        $this->expectException(\InvalidArgumentException::class);
        /** @var ResourceEntity|ObjectProphecy $expectedResourceEntity */
        $expectedResourceEntity = $this->prophesize(ResourceEntity::class);
        $expectedResourceEntity->getTier()
            ->willReturn($expectedTier);

        $this->refiningHelper->calculateLowerResource($tier, [$expectedResourceEntity->reveal()]);
    }

    public function getLowerResourceNull(): array
    {
        return [['3', '4'], ['4', '41'], ['52', '53'], ['83', '8'], ['2', '31']];
    }

    /**
     * @dataProvider getProfit
     */
    public function testCalculateProfit(
        int $refinedResourcePrice,
        int $rawResourcePrice,
        int $lowerResourcePrice,
        int $amountRawResource,
        float $percentage,
        float $expectedProfit
    ): void {
        $this->assertEqualsWithDelta(
            $expectedProfit,
            $this->refiningHelper->calculateProfit(
                $refinedResourcePrice,
                $rawResourcePrice,
                $lowerResourcePrice,
                $amountRawResource,
                $percentage
            ),
            0.00000001
        );
    }

    public function getProfit(): array
    {
        return [
            [104, 56, 35, 2, 53.9, 29.472999999999985],
            [134, 67, 104, 2, 53.9, 15.571999999999989],
            [600, 247, 134, 3, 53.9, 157.625],
            [2309, 777, 600, 4, 53.9, 449.5269999999998],
            [7497, 2023, 2309, 5, 53.9, 1282.2309999999998],
            [23564, 7498, 7497, 5, 53.9, 1293.3329999999987],
        ];
    }

    /**
     * @dataProvider getRefiningAmount
     */
    public function testCalculateRefiningAmount(string $tier, int $expectedAmount): void
    {
        $this->assertEquals($expectedAmount, $this->refiningHelper->calculateRefiningAmount($tier));
    }

    public function getRefiningAmount(): array
    {
        return [
            ['3', 968],
            ['4', 10000],
            ['41', 5000],
            ['42', 3333],
            ['43', 2000],
            ['5', 5000],
            ['51', 3000],
            ['52', 1765],
            ['53', 1035],
            ['6', 3000],
            ['61', 1667],
            ['62', 1000],
            ['63', 566],
            ['7', 1667],
            ['71', 968],
            ['72', 556],
            ['73', 319],
            ['8', 968],
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
        $this->assertEquals($expectedProfit, $this->refiningHelper->calculateTotalProfit($amount, $singleProfit));
    }

    public function getTotalProfit(): array
    {
        return [[5, 1250, 6250.0], [3, 36.67, 110.01], [25, 25, 625.0]];
    }
}
