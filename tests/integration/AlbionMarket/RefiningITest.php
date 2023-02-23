<?php

declare(strict_types=1);

namespace integration\AlbionMarket;

use MZierdt\Albion\AlbionMarket\RefiningService;
use MZierdt\Albion\Entity\AdvancedEntities\RefiningEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class RefiningITest extends TestCase
{
    use ProphecyTrait;

    public function testRefining(): void
    {
        $refiningService = new RefiningService();
        $delta = 0.00001;
        $resourceEntity = (new ResourceEntity())
            ->setTier(71)
            ->setName('leather')
            ->setCity('TestCity')
            ->setSellOrderPrice(21996)
            ->setBuyOrderPrice(19902);

        $baseRefiningEntity = new RefiningEntity($resourceEntity);

        $refiningEntity = $refiningService->calculateRefiningEntity(
            $baseRefiningEntity,
            $this->getRawResources(),
            $this->getResources(),
            'TestCity'
        );

        $this->assertEquals('TestCity', $refiningEntity->getCity());

        $this->assertEqualswithDelta(19815.163, $refiningEntity->getMaterialCostSell(), $delta);
        $this->assertEqualsWithDelta(751.097, $refiningEntity->getProfitSell(), $delta);
        $this->assertEquals(111, $refiningEntity->getProfitPercentageSell());
        $this->assertEquals('C', $refiningEntity->getProfitGradeSell());

        $this->assertEqualswithDelta(27208.239, $refiningEntity->getMaterialCostBuy(), $delta);
        $this->assertEqualsWithDelta(-6641.979, $refiningEntity->getProfitBuy(), $delta);
        $this->assertEquals(80.84, $refiningEntity->getProfitPercentageBuy());
        $this->assertEquals('D', $refiningEntity->getProfitGradeBuy());

        $this->assertEquals(968, $refiningEntity->getAmount());
        $this->assertEquals(7, $refiningEntity->getTierColor());
        $this->assertEquals(5, $refiningEntity->getAmountRawResource());
    }

    private function getRawResources(): array
    {
        $rawA = (new ResourceEntity())
            ->setTier(71)
            ->setName('hide')
            ->setCity('TestCity')
            ->setSellOrderPrice(7448)
            ->setBuyOrderPrice(7021)
            ->setRaw(true);
        return [$rawA];
    }

    private function getResources(): array
    {
        $refinedA = (new ResourceEntity())
            ->setTier(61)
            ->setName('leather')
            ->setCity('TestCity')
            ->setSellOrderPrice(7910)
            ->setBuyOrderPrice(7878);
        return [$refinedA];
    }
}
