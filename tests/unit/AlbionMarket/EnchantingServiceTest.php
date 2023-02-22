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
    public function testCalculateMaterialCost(float $expectedCost, int $materialAmount, int $buyOrderPrice): void
    {
        $this->assertEqualsWithDelta(
            $expectedCost,
            $this->enchantingService->calculateTotalMaterialCost($materialAmount, $buyOrderPrice),
            0.00001
        );
    }

    public function provideMaterialCost(): array
    {
        return [[1638, 48, 35], [112320, 96, 1200], [62478, 144, 445], [1310.4, 192, 7]];
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
}
