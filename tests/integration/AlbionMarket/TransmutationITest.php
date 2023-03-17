<?php

declare(strict_types=1);

namespace integration\AlbionMarket;

use MZierdt\Albion\AlbionMarket\TransmutationService;
use MZierdt\Albion\Entity\AdvancedEntities\TransmutationEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;

class TransmutationITest extends TestCase
{
    public function testTransmutation(): void
    {
        $transmutationService = new TransmutationService();
        $delta = 0.00001;

        $baseTransmutationEntity = new TransmutationEntity('40to61', [50, 60, 61], 'leather');

        $transmutationCost = [
            50 => ['tier' => 1180],
            60 => ['tier' => 1760],
            61 => ['tier' => 3521, 'enchantment' => 2640],
        ];
        $refiningEntity = $transmutationService->calculateTransmutationEntity(
            $baseTransmutationEntity,
            $this->getResources(),
            40,
            61,
            $transmutationCost,
            0.0,
            'TestCity'
        );

        $this->assertEquals('TestCity', $refiningEntity->getCity());

        $this->assertEqualswithDelta(6353, $refiningEntity->getMaterialCostSell(), $delta);
        $this->assertEqualsWithDelta(1042.85, $refiningEntity->getProfitSell(), $delta);
        $this->assertEquals(121.38, $refiningEntity->getProfitPercentageSell());
        $this->assertEquals('C', $refiningEntity->getProfitGradeSell());

        $this->assertEqualswithDelta(5758.425, $refiningEntity->getMaterialCostBuy(), $delta);
        $this->assertEqualsWithDelta(1637.425, $refiningEntity->getProfitBuy(), $delta);
        $this->assertEquals(133.91, $refiningEntity->getProfitPercentageBuy());
        $this->assertEquals('B', $refiningEntity->getProfitGradeBuy());

        $this->assertEquals(4, $refiningEntity->getTierColor());
        $this->assertEquals(5580, $refiningEntity->getTransmutationPrice());
    }

    private function getResources(): array
    {
        $refinedA = (new ResourceEntity())
            ->setTier(61)
            ->setRealName('leather')
            ->setCity('TestCity')
            ->setSellOrderPrice(7910)
            ->setBuyOrderPrice(7878);
        $refinedB = (new ResourceEntity())
            ->setTier(40)
            ->setRealName('leather')
            ->setCity('TestCity')
            ->setSellOrderPrice(773)
            ->setBuyOrderPrice(183);
        return [$refinedA, $refinedB];
    }
}
