<?php

namespace integration\Service;

use MZierdt\Albion\Entity\BlackMarketCraftingEntity;
use MZierdt\Albion\Service\BlackMarketCraftingService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BlackMarketCraftingServiceTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketCraftingService $bmcService;

    protected function setUp(): void
    {
        $container = require __DIR__ . '/../../../container.php';

        $this->bmcService = $container->get(BlackMarketCraftingService::class);
    }

    public function testBlackMarketCraftingService(): void
    {
        $delta = 0.00000001;
        $testData = $this->bmcService->getDataForCity('TestCity', 500, 25.2, 1200, 'TestCity', 1);

        foreach ($testData as $bmcEntity) {
            $this->assertEqualsWithDelta(1.4545454545455, $bmcEntity[0]->getJournalAmountPerItem(), $delta);

            $this->assertEquals(8, $bmcEntity[0]->getTotalAmount());
            $this->assertEquals(160, $bmcEntity[0]->getPrimResourceAmount());
            $this->assertEquals(96, $bmcEntity[0]->getSecResourceAmount());
            $this->assertEquals(12, $bmcEntity[0]->getJournalAmount());
            $this->assertEqualsWithDelta(182.4, $bmcEntity[0]->getTotalItemWeight(), $delta);

            $this->assertEqualsWithDelta(11059.2, $bmcEntity[0]->getCraftingFee(), $delta);
            $this->assertEqualsWithDelta(452940.0, $bmcEntity[0]->getProfitJournals(), $delta);
            $this->assertEqualsWithDelta(1069829.7919999997, $bmcEntity[0]->getProfit(), $delta);
            $this->assertEqualsWithDelta(2139.6595839999995, $bmcEntity[0]->getWeightProfitQuotient(), $delta);

            $this->assertEquals('S', $bmcEntity[0]->getColorGrade());
            $this->assertEqualsWithDelta(495360.0, $bmcEntity[0]->getFameAmount(), $delta);
            $this->assertEquals('7', $bmcEntity[0]->getTierColor());
            $this->assertEquals(3535936, $bmcEntity[0]->getItemValue());

            $this->assertEquals('3h_axe', $bmcEntity[0]->getItem()->getName());
            $this->assertEquals('metalBar', $bmcEntity[0]->getPrimResource()->getName());
            $this->assertEquals('planks', $bmcEntity[0]->getSecResource()->getName());
            $this->assertEquals('journal_warrior_empty', $bmcEntity[0]->getJournalEntityEmpty()->getName());
            $this->assertEquals('journal_warrior_full', $bmcEntity[0]->getJournalEntityFull()->getName());
        }
    }
}