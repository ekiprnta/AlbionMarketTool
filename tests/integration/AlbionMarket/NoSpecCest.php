<?php

declare(strict_types=1);

namespace integration\AlbionMarket;

use MZierdt\Albion\AlbionMarket\NoSpecCraftingService;
use MZierdt\Albion\Entity\AdvancedEntities\NoSpecEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class NoSpecCest extends TestCase
{
    use ProphecyTrait;

    public function testNoSpec(): void
    {
        $noSpecCraftingService = new NoSpecCraftingService();
        $delta = 0.00001;
        $itemEntity = (new ItemEntity())
            ->setTier(71)
            ->setName('capeitem_fw_bridgewatch')
            ->setCity('TestCity')
            ->setPrimaryResource('cape')
            ->setPrimaryResourceAmount(1)
            ->setSecondaryResource('Beastheart')
            ->setSecondaryResourceAmount(5)
            ->setSellOrderPrice(469980)
            ->setBuyOrderPrice(154442);

        $baseNoSpecEntity = new NoSpecEntity($itemEntity);

        $noSpecEntity = $noSpecCraftingService->calculateNoSpecEntity(
            $baseNoSpecEntity,
            $this->getItems(),
            $this->getHeartsAndSigils(),
            $this->getArtifacts(),
            'TestCity'
        );

        $this->assertEquals('TestCity', $noSpecEntity->getCity());

        $this->assertEqualswithDelta(408888, $noSpecEntity->getMaterialCostSell(), $delta);
        $this->assertEqualsWithDelta(30543.3, $noSpecEntity->getProfitSell(), $delta);
        $this->assertEquals(114.94, $noSpecEntity->getProfitPercentageSell());
        $this->assertEquals('C', $noSpecEntity->getProfitGradeSell());

        $this->assertEqualswithDelta(357077, $noSpecEntity->getMaterialCostBuy(), $delta);
        $this->assertEqualsWithDelta(82354.3, $noSpecEntity->getProfitBuy(), $delta);
        $this->assertEquals(131.62, $noSpecEntity->getProfitPercentageBuy());
        $this->assertEquals('B', $noSpecEntity->getProfitGradeBuy());

        $this->assertEquals(7, $noSpecEntity->getTierColor());
    }

    private function getItems(): array
    {
        $itemEntity = (new ItemEntity())
            ->setTier(71)
            ->setName('cape')
            ->setCity('TestCity')
            ->setPrimaryResource('cloth')
            ->setPrimaryResourceAmount(4)
            ->setSecondaryResource('leather')
            ->setSecondaryResourceAmount(4)
            ->setSellOrderPrice(108993)
            ->setBuyOrderPrice(75902);
        return [$itemEntity];
    }

    private function getHeartsAndSigils(): array
    {
        $heartA = (new MaterialEntity())
            ->setTier(10)
            ->setRealName('Beastheart')
            ->setCity('TestCity')
            ->setSellOrderPrice(42979)
            ->setBuyOrderPrice(39235);
        return [$heartA];
    }

    private function getArtifacts(): array
    {
        $artifact = (new MaterialEntity())
            ->setTier(71)
            ->setName('capeitem_fw_bridgewatch_bp')
            ->setSellOrderPrice(112000)
            ->setBuyOrderPrice(85000);
        return [$artifact];
    }
}