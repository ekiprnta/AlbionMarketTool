<?php

declare(strict_types=1);

namespace integration\AlbionMarket;

use MZierdt\Albion\AlbionMarket\BlackMarketCraftingService;
use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BmCraftingITest extends TestCase
{
    use ProphecyTrait;

    public function testBmCrafting(): void
    {
        $bmcService = new BlackMarketCraftingService();
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
            ->setBuyOrderPrice(367983)
            ->refreshFame();

        $baseBmcEntity = new BlackMarketCraftingEntity($itemEntity);

        $bmcEntity = $bmcService->calculateBmcEntity(
            $baseBmcEntity,
            $this->getResources(),
            $this->getJournals(),
            [
                71 => [
                    32 => 50,
                ],
            ],
            'TestCity'
        );

        $tome = (new MaterialEntity())->setBuyOrderPrice(17999);

        $bmcService->calculateProfitByPercentage($bmcEntity, 47.9, $tome);

        $this->assertEquals('TestCity', $bmcEntity->getCity());
        $this->assertEquals(3096000, $bmcEntity->getFameAmount());
        $this->assertEqualsWithDelta(1.45, $bmcEntity->getJournalAmountPerItem(), $delta);

        $this->assertEqualswithDelta(287219.99375, $bmcEntity->getMaterialCostSell(), $delta);
        $this->assertEqualsWithDelta(58692.606, $bmcEntity->getProfitSell(), $delta);
        $this->assertEquals(125.59, $bmcEntity->getProfitPercentageSell());
        $this->assertEquals('C', $bmcEntity->getProfitGradeSell());

        $this->assertEqualswithDelta(253048.69785, $bmcEntity->getMaterialCostBuy(), $delta);
        $this->assertEqualsWithDelta(92863.902, $bmcEntity->getProfitBuy(), $delta);
        $this->assertEquals(142.55, $bmcEntity->getProfitPercentageBuy());
        $this->assertEquals('B', $bmcEntity->getProfitGradeBuy());

        $this->assertEquals(50, $bmcEntity->getAmount());
        $this->assertEquals(7, $bmcEntity->getTierColor());
    }

    private function getResources(): array
    {
        $refinedA = (new ResourceEntity())
            ->setTier(71)
            ->setRealName('metalBar')
            ->setCity('TestCity')
            ->setSellOrderPrice(17876)
            ->setBuyOrderPrice(16000);
        $refinedB = (new ResourceEntity())
            ->setTier(71)
            ->setRealName('planks')
            ->setCity('TestCity')
            ->setSellOrderPrice(14555)
            ->setBuyOrderPrice(13213);
        return [$refinedA, $refinedB];
    }

    private function getJournals(): array
    {
        $journalFull = (new JournalEntity())
            ->setTier(70)
            ->setFameToFill(28380)
            ->setFillStatus('full')
            ->setClass('warrior')
            ->setSellOrderPrice(41973)
            ->setBuyOrderPrice(42029);
        $journalEmpty = (new JournalEntity())
            ->setTier(70)
            ->setFameToFill(28380)
            ->setFillStatus('empty')
            ->setClass('warrior')
            ->setSellOrderPrice(9841)
            ->setBuyOrderPrice(7041);
        return [$journalFull, $journalEmpty];
    }
}
