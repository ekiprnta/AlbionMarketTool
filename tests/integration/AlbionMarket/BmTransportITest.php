<?php

declare(strict_types=1);

namespace integration\AlbionMarket;

use MZierdt\Albion\AlbionMarket\BlackMarketTransportingService;
use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BmTransportITest extends TestCase
{
    use ProphecyTrait;

    public function testBmTransport(): void
    {
        $bmtService = new BlackMarketTransportingService();
        $delta = 0.00001;
        $itemEntity = (new ItemEntity())
            ->setTier(71)
            ->setName('2h_axe')
            ->setCity('TestCity')
            ->setPrimaryResource('metalBar')
            ->setPrimaryResourceAmount(20)
            ->setSecondaryResource('planks')
            ->setSecondaryResourceAmount(12)
            ->setSellOrderPrice(369960)
            ->setBuyOrderPrice(367983);

        $baseBmtEntity = new BlackMarketTransportEntity($itemEntity);

        $bmtEntity = $bmtService->calculateBmtEntity(
            $baseBmtEntity,
            $this->getItems(),
            [8 => 100, 16 => 100, 24 => 25, 32 => 20],
            'TestCity'
        );

        $this->assertEquals('TestCity', $bmtEntity->getCity());

        $this->assertEqualswithDelta(339965, $bmtEntity->getMaterialCostSell(), $delta);
        $this->assertEqualsWithDelta(5947.6, $bmtEntity->getProfitSell(), $delta);
        $this->assertEquals(108.82, $bmtEntity->getProfitPercentageSell());
        $this->assertEquals('C', $bmtEntity->getProfitGradeSell());

        $this->assertEqualswithDelta(229722.675, $bmtEntity->getMaterialCostBuy(), $delta);
        $this->assertEqualsWithDelta(114342.105, $bmtEntity->getProfitBuy(), $delta);
        $this->assertEquals(156.18, $bmtEntity->getProfitPercentageBuy());
        $this->assertEquals('B', $bmtEntity->getProfitGradeBuy());

        $this->assertEquals(20, $bmtEntity->getAmount());
        $this->assertEquals(7, $bmtEntity->getTierColor());
    }

    private function getItems(): array
    {
        $itemEntityA = (new ItemEntity())
            ->setTier(71)
            ->setName('2h_axe')
            ->setCity('Black Market')
            ->setPrimaryResource('metalBar')
            ->setPrimaryResourceAmount(20)
            ->setSecondaryResource('planks')
            ->setSecondaryResourceAmount(12)
            ->setSellOrderPrice(339965)
            ->setBuyOrderPrice(235613);
        return [$itemEntityA];
    }
}