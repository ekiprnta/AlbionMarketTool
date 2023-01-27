<?php

namespace unit\Service;

use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\Service\TransmutationHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TransmutationHelperTest extends TestCase
{
    use ProphecyTrait;

    private TransmutationHelper $tranHelper;

    /** @dataProvider provideResources */
    public function testReformatResources(array $result, array $resources): void
    {
        $this->assertEquals($result, $this->tranHelper->reformatResources($resources));
    }

    public function provideResources(): array
    {
        [$resourceA, $resourceB] = $this->getResources();
        return [
            [['2' => $resourceA], [$resourceA]],
            [['41' => $resourceB], [$resourceB]],
        ];
    }

    private function getResources()
    {
        $resourceA = new ResourceEntity([
            'tier' => '2',
            'name' => 'cloth',
            'city' => 'Fort Sterling',
            'realName' => 'cloth',
            'sellOrderPrice' => '27',
            'sellOrderPriceDate' => '2022-12-08 10:45:00',
            'buyOrderPrice' => '26',
            'buyOrderPriceDate' => '2022-12-08 10:45:00',
            'bonusCity' => 'Lymhurst',
            'amountInStorage' => null,
        ]);
        $resourceB = new ResourceEntity([
            'tier' => '41',
            'name' => 'leather',
            'city' => 'Bridgewatch',
            'realName' => 'cloth',
            'sellOrderPrice' => '376',
            'sellOrderPriceDate' => '2022-02-08 10:45:00',
            'buyOrderPrice' => '87',
            'buyOrderPriceDate' => '2022-01-08 10:45:00',
            'bonusCity' => 'Lymhurst',
            'amountInStorage' => null,
        ]);
        return [$resourceA, $resourceB];
    }

    /** @dataProvider provideCost */
    public function testCalculateProfit(float $profit, int $startPrice, int $endPrice, float $transmuteCost): void
    {
        $this->assertEquals($profit, $this->tranHelper->calculateProfit($startPrice, $endPrice, $transmuteCost));
    }

    public function provideCost(): array
    {
        return [
            [40, 10, 200, 150],
            [23379353, 234, 23456787, 77200],
        ];
    }

    /** @dataProvider provideStartAndEnd */
    public function testCalculateStartAndEnd(array $result, string $pathName): void
    {
        $this->assertEquals($result, $this->tranHelper->calculateStartAndEnd($pathName));
    }

    public function provideStartAndEnd(): array
    {
        return [
            [[4, 62], '4to62'],
            [[51, 71], '51to71'],
            [[62, 74], '62to74'],
        ];
    }

    /** @dataProvider provideTransmutationPrice */
    public function testCalculateTransmutationPrice(float $result, array $transmutationPath,): void
    {
        $transmutationCost = [
            "5" => [
                "tier" => 1180
            ],
            "51" => [
                "enchantment" => 1760,
                "tier" => 2351
            ],
            "52" => [
                "enchantment" => 3531,
                "tier" => 4701
            ],
            "53" => [
                "enchantment" => 7042,
                "tier" => 9394
            ],
            "54" => [
                "enchantment" => 70440,
                "tier" => 93921
            ],
            "6" => [
                "tier" => 1760
            ],
            "61" => [
                "enchantment" => 2640,
                "tier" => 3521
            ],
            "62" => [
                "enchantment" => 5282,
                "tier" => 7042
            ],
            "63" => [
                "enchantment" => 10564,
                "tier" => 14085
            ],
        ];
        $this->assertEqualsWithDelta(
            $result,
            $this->tranHelper->calculateTransmutationPrice($transmutationPath, '4', $transmutationCost, 0.01),
            0.00001
        );
    }

    public function provideTransmutationPrice(): array
    {
        return [
            [21211.74, [5, 6, 61, 62, 63]],
            [2910.6, [5, 51]],
            [5524.2, [5, 6, 61]],
        ];
    }

    /** @dataProvider provideResourceTier */
    public function testCalculateResource(?ResourceEntity $result, string $tier): void
    {
        $resources = $this->getResources();

        $this->assertEquals($result, $this->tranHelper->calculateResource($resources, $tier));
    }

    public function provideResourceTier(): array
    {
        [$resourceA, $resourceB] = $this->getResources();
        return [
            [$resourceA, '2'],
            [$resourceB, '41'],
            [null, '52'],
        ];
    }

    protected function setUp(): void
    {
        $this->tranHelper = new TransmutationHelper();
    }
}