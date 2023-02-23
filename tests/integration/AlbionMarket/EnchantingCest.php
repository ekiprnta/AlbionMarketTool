<?php

declare(strict_types=1);

namespace integration\AlbionMarket;

use MZierdt\Albion\AlbionMarket\EnchantingService;
use MZierdt\Albion\Entity\AdvancedEntities\EnchantingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class EnchantingCest extends TestCase
{
    use ProphecyTrait;

    public function testEnchanting(): void
    {
        $enchantingService = new EnchantingService();
        $delta = 0.00001;
        $itemEntity = (new ItemEntity())
            ->setTier(71)
            ->setName('2h_axe')
            ->setCity('TestCity')
            ->setPrimaryResource('metalBar')
            ->setPrimaryResourceAmount(20)
            ->setSecondaryResource('planks')
            ->setSecondaryResourceAmount(12)
            ->setSellOrderPrice(339965)
            ->setBuyOrderPrice(235613);

        $baseEnchantingEntity = new EnchantingEntity($itemEntity);

        $enchantingEntity = $enchantingService->calculateEnchantingEntity(
            $baseEnchantingEntity,
            $this->getMaterials(),
            $this->getItems(),
            'TestCity'
        );

        $this->assertEquals('TestCity', $enchantingEntity->getCity());

        $this->assertEqualswithDelta(null, $enchantingEntity->getMaterialCostSell(), $delta);
        $this->assertEqualsWithDelta(154332.15, $enchantingEntity->getProfitSell(), $delta);
        $this->assertEquals(129.16, $enchantingEntity->getProfitPercentageSell());
        $this->assertEquals('C', $enchantingEntity->getProfitGradeSell());

        $this->assertEqualswithDelta(403200, $enchantingEntity->getMaterialCostBuy(), $delta);
        $this->assertEqualsWithDelta(258684.15, $enchantingEntity->getProfitBuy(), $delta);
        $this->assertEquals(150.26, $enchantingEntity->getProfitPercentageBuy());
        $this->assertEquals('B', $enchantingEntity->getProfitGradeBuy());

        $this->assertEquals(7, $enchantingEntity->getTierColor());
        $this->assertEquals(1, $enchantingEntity->getBaseEnchantment());
        $this->assertEquals(192, $enchantingEntity->getMaterialAmount());
    }

    private function getMaterials(): array
    {
        $materialA = (new MaterialEntity())
            ->setTier(70)
            ->setName('soul')
            ->setCity('TestCity')
            ->setSellOrderPrice(2177)
            ->setBuyOrderPrice(2100);
        return [$materialA];
    }

    private function getItems(): array
    {
        $itemEntityA = (new ItemEntity())
            ->setTier(72)
            ->setName('2h_axe')
            ->setCity('Black Market')
            ->setPrimaryResource('metalBar')
            ->setPrimaryResourceAmount(20)
            ->setSecondaryResource('planks')
            ->setSecondaryResourceAmount(12)
            ->setSellOrderPrice(959890)
            ->setBuyOrderPrice(665874);
        return [$itemEntityA];
    }
}
