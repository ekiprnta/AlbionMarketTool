<?php

declare(strict_types=1);

namespace unit\AlbionMarket;

use MZierdt\Albion\AlbionMarket\EnchantingService;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class EnchantingServiceTest extends TestCase
{
    use ProphecyTrait;

    private EnchantingService $enchantingService;

    /**
     * @dataProvider provideEnchantments
     */
    public function testGetEnchantment(int $enchantment, int $tier): void
    {
        $this->assertEquals($enchantment, $this->enchantingService->getEnchantment($tier));
    }

    public function provideEnchantments(): array
    {
        return [[0, 70], [1, 71], [2, 72], [3, 73]];
    }

    public function testCalculateHigherEnchantmentItem(): void
    {
        $expectedItemEntity = (new ItemEntity())->setName('2h_axe')
            ->setTier(71);

        $this->assertEquals(
            $expectedItemEntity,
            $this->enchantingService->calculateHigherEnchantmentItem(70, '2h_axe', $this->getItems())
        );
    }

    public function getItems(): array
    {
        $itemA = (new ItemEntity())->setName('2h_axe')
            ->setTier(71);
        $itemB = (new ItemEntity())->setName('2h_axe')
            ->setTier(72);

        return [$itemA, $itemB];
    }

    public function testCalculateHigherEnchantmentItemException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->enchantingService->calculateHigherEnchantmentItem(70, '2_axe', []);
    }

    /**
     * @dataProvider provideMaterials
     */
    public function testCalculateEnchantmentMaterial(MaterialEntity $expectedMaterial, int $tier): void
    {
        $this->assertEquals(
            $expectedMaterial,
            $this->enchantingService->calculateEnchantmentMaterial($tier, $this->getMaterials())
        );
    }

    public function getMaterials(): array
    {
        $materialA = (new MaterialEntity())->setName('rune')
            ->setTier(70);
        $materialB = (new MaterialEntity())->setName('soul')
            ->setTier(70);
        $materialC = (new MaterialEntity())->setName('relic')
            ->setTier(70);

        return [$materialA, $materialB, $materialC];
    }

    public function provideMaterials(): array
    {
        return [
            [(new MaterialEntity())->setName('rune')->setTier(70), 70],
            [(new MaterialEntity())->setName('soul')->setTier(70), 71],
            [(new MaterialEntity())->setName('relic')->setTier(70), 72],
        ];
    }

    public function testCalculateEnchantmentMaterialException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->enchantingService->calculateEnchantmentMaterial(70, []);
    }

    /**
     * @dataProvider provideTotalAmount
     */
    public function testCalculateMaterialAmount(int $materialAmount, int $resourceAmount): void
    {
        $this->assertEquals($materialAmount, $this->enchantingService->calculateMaterialAmount($resourceAmount));
    }

    public function provideTotalAmount(): array
    {
        return [[48, 8], [96, 16], [144, 24], [192, 32]];
    }

    /**
     * @dataProvider provideMaterialCost
     */
    public function testCalculateMaterialCost(float $expectedCost, int $materialAmount, float $materialPrice): void
    {
        $this->assertEqualsWithDelta(
            $expectedCost,
            $this->enchantingService->calculateTotalMaterialCost($materialPrice, $materialAmount),
            0.00001
        );
    }

    public function provideMaterialCost(): array
    {
        return [[1680, 48, 35], [115200, 96, 1200], [64080, 144, 445], [1344, 192, 7]];
    }

    /**
     * @dataProvider provideProfit
     */
    public function testCalculateProfit(
        float $expectedCost,
        int $baseItemPrice,
        int $enchantedItemPrice,
        float $materialCost
    ): void {
        $this->assertEquals(
            $expectedCost,
            $this->enchantingService->calculateProfit($baseItemPrice, $enchantedItemPrice, $materialCost)
        );
    }

    public function provideProfit(): array
    {
        return [[-3075, 5000, 7500, 250], [-64069.07, 45678, 50000, 56778]];
    }

    protected function setUp(): void
    {
        $this->enchantingService = new EnchantingService();
    }

    /**
     * @dataProvider provideAmount
     */
    public function testCalculateAmount(int $result, int $tier, int $resourceAmount): void
    {
        $config = [
            40 => [8 => 4000, 16 => 4000, 24 => 1000, 32 => 800],
            52 => [8 => 100, 16 => 100, 24 => 25, 32 => 25],
            61 => [8 => 750, 16 => 750, 24 => 150, 32 => 150],
            84 => [8 => 1, 16 => 1, 24 => 1, 32 => 1],
        ];

        $this->assertEquals($result, $this->enchantingService->calculateAmount($tier, $resourceAmount, $config));
    }

    public function provideAmount(): array
    {
        return [[100, 52, 16], [1, 84, 32], [1000, 40, 24], [750, 61, 8]];
    }
}
