<?php

namespace unit\AlbionMarket;

use MZierdt\Albion\AlbionMarket\TransmutationService;
use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TransmutationServiceTest extends TestCase
{
    use ProphecyTrait;

    private TransmutationService $tranService;

    private function getResources(): array
    {
        $resourceA = (new ResourceEntity())
            ->setTier(71)
            ->setName('metalBar')
            ->setCity('TestCity')
            ->setRealName('metalBar')
            ->setSellOrderPrice(13986)
            ->setBuyOrderPrice(12235);
        $resourceB = (new ResourceEntity())
            ->setTier(60)
            ->setName('metalBar')
            ->setCity('Sterling')
            ->setRealName('metalBar')
            ->setSellOrderPrice(1099)
            ->setBuyOrderPrice(1098)
            ->setRaw(true);
        return [$resourceA, $resourceB];
    }

    /**
     * @dataProvider provideCost
     */
    public function testCalculateProfit(float $profit, int $startPrice, int $endPrice, float $transmuteCost): void
    {
        $this->assertEquals($profit, $this->tranService->calculateProfit($startPrice, $endPrice, $transmuteCost));
    }

    public function provideCost(): array
    {
        return [[-340.65, 10, 200, 150], [-23533768.21, 234, 23456787, 77200]];
    }

    /**
     * @dataProvider provideStartAndEnd
     */
    public function testCalculateStartAndEnd(array $result, string $pathName): void
    {
        $this->assertEquals($result, $this->tranService->calculateStartAndEnd($pathName));
    }

    public function provideStartAndEnd(): array
    {
        return [[[4, 62], '4to62'], [[51, 71], '51to71'], [[62, 74], '62to74']];
    }

    /**
     * @dataProvider provideTransmutationPrice
     */
    public function testCalculateTransmutationPrice(float $result, array $transmutationPath): void
    {
        $transmutationCost = [
            '50' => [
                'tier' => 1180,
            ],
            '51' => [
                'enchantment' => 1760,
                'tier' => 2351,
            ],
            '52' => [
                'enchantment' => 3531,
                'tier' => 4701,
            ],
            '53' => [
                'enchantment' => 7042,
                'tier' => 9394,
            ],
            '54' => [
                'enchantment' => 70440,
                'tier' => 93921,
            ],
            '60' => [
                'tier' => 1760,
            ],
            '61' => [
                'enchantment' => 2640,
                'tier' => 3521,
            ],
            '62' => [
                'enchantment' => 5282,
                'tier' => 7042,
            ],
            '63' => [
                'enchantment' => 10564,
                'tier' => 14085,
            ],
        ];
        $this->assertEqualsWithDelta(
            $result,
            $this->tranService->calculateTransmutationPrice($transmutationPath, '40', $transmutationCost, 0.00),
            0.00001
        );
    }

    public function provideTransmutationPrice(): array
    {
        return [
            [21426, [50, 60, 61, 62, 63]],
            [2940, [50, 51]],
            [5580, [50, 60, 61]]
        ];
    }

    /**
     * @dataProvider provideResourceTier
     */
    public function testCalculateResource(?ResourceEntity $result, int $tier): void
    {
        $resources = $this->getResources();

        $this->assertEquals($result, $this->tranService->calculateResource($resources, $tier, 'metalBar'));
    }

    public function provideResourceTier(): array
    {
        [$resourceA, $resourceB] = $this->getResources();
        return [[$resourceA, 71], [$resourceB, 60]];
    }

    public function testCalculateResourceException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $resources = $this->getResources();

        $this->tranService->calculateResource($resources, 52, 'metalBar');
    }

    protected function setUp(): void
    {
        $this->tranService = new TransmutationService();
    }
}
