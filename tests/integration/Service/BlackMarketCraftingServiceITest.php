<?php

namespace integration\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\JournalRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\BlackMarketCraftingHelper;
use MZierdt\Albion\Service\BlackMarketCraftingService;
use MZierdt\Albion\Service\ConfigService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class BlackMarketCraftingServiceITest extends TestCase
{
    use ProphecyTrait;

    public function testBlackMarketCraftingServiceA(): void // TOdo full Integration!
    {
        /** @var JournalRepository|ObjectProphecy $journalRepository */
        $journalRepository = $this->prophesize(JournalRepository::class);
        /** @var ItemRepository|ObjectProphecy $itemRepository */
        $itemRepository = $this->prophesize(ItemRepository::class);
        /** @var ResourceRepository|ObjectProphecy $resourceRepository */
        $resourceRepository = $this->prophesize(ResourceRepository::class);

        $itemRepository->getBlackMarketItemsFromCity('TestCity')
            ->willReturn($this->getItems());
        $resourceRepository->getResourcesByCity('TestCity')
            ->willReturn($this->getResources());
        $journalRepository->getJournalsFromCity('TestCity')
            ->willReturn($this->getJournals());

        $bmcService = new BlackMarketCraftingService(
            $itemRepository->reveal(),
            $resourceRepository->reveal(),
            $journalRepository->reveal(),
            new BlackMarketCraftingHelper(),
            new ConfigService()
        );

        $delta = 0.00001;
        $testData = $bmcService->getDataForCity('TestCity', 25.2, 1200, 'TestCity', 1);

        foreach ($testData as $bmcEntity) {
            $this->assertEqualsWithDelta(1.4545454545455, $bmcEntity[0]->getJournalAmountPerItem(), $delta);

            $this->assertEquals(20, $bmcEntity[0]->getTotalAmount());
            $this->assertEquals(400, $bmcEntity[0]->getPrimResourceAmount());
            $this->assertEquals(240, $bmcEntity[0]->getSecResourceAmount());
            $this->assertEqualsWithDelta(29.090909090909093, $bmcEntity[0]->getJournalAmount(), $delta);

            $this->assertEqualsWithDelta(11059.2, $bmcEntity[0]->getCraftingFee(), $delta);
            $this->assertEqualsWithDelta(-1958.8363636364, $bmcEntity[0]->getProfitJournals(), $delta);
            $this->assertEqualsWithDelta(1556854.4436363627, $bmcEntity[0]->getProfit(), $delta);
            $this->assertEqualsWithDelta(77842.72218181813, $bmcEntity[0]->getProfitQuotient(), $delta);

            $this->assertEquals('S', $bmcEntity[0]->getColorGrade());
            $this->assertEqualsWithDelta(1238400.0, $bmcEntity[0]->getFameAmount(), $delta);
            $this->assertEquals(7, $bmcEntity[0]->getTierColor());
            $this->assertEquals(8839840, $bmcEntity[0]->getItemValue());

            $this->assertEquals('3h_axe', $bmcEntity[0]->getItem()->getName());
            $this->assertEquals('metalBar', $bmcEntity[0]->getPrimResource()->getName());
            $this->assertEquals('planks', $bmcEntity[0]->getSecResource()->getName());
            $this->assertEquals('journal_warrior_empty', $bmcEntity[0]->getJournalEntityEmpty()->getName());
            $this->assertEquals('journal_warrior_full', $bmcEntity[0]->getJournalEntityFull()->getName());
        }
    }

    public function testBlackMarketCraftingServiceB(): void // TOdo full Integration!
    {
        /** @var JournalRepository|ObjectProphecy $journalRepository */
        $journalRepository = $this->prophesize(JournalRepository::class);
        /** @var ItemRepository|ObjectProphecy $itemRepository */
        $itemRepository = $this->prophesize(ItemRepository::class);
        /** @var ResourceRepository|ObjectProphecy $resourceRepository */
        $resourceRepository = $this->prophesize(ResourceRepository::class);

        $itemRepository->getBlackMarketItemsFromCity('TestCity')
            ->willReturn($this->getItems());
        $resourceRepository->getResourcesByCity('TestCity')
            ->willReturn($this->getResources());
        $journalRepository->getJournalsFromCity('TestCity')
            ->willReturn($this->getJournals());

        $bmcService = new BlackMarketCraftingService(
            $itemRepository->reveal(),
            $resourceRepository->reveal(),
            $journalRepository->reveal(),
            new BlackMarketCraftingHelper(),
            new ConfigService()
        );

        $delta = 0.000001;
        $testData = $bmcService->getDataForCity('TestCity', 500, 0, 0, '', 2);

        foreach ($testData as $bmcEntity) {
            $this->assertEqualsWithDelta(1.4545454545455, $bmcEntity[0]->getJournalAmountPerItem(), $delta);

            $this->assertEquals(20, $bmcEntity[0]->getTotalAmount());
            $this->assertEquals(400, $bmcEntity[0]->getPrimResourceAmount());
            $this->assertEquals(240, $bmcEntity[0]->getSecResourceAmount());
            $this->assertEqualsWithDelta(29.090909090909093, $bmcEntity[0]->getJournalAmount(), $delta);

            $this->assertEqualsWithDelta(0, $bmcEntity[0]->getCraftingFee(), $delta);
            $this->assertEqualsWithDelta(-1958.8363636363913, $bmcEntity[0]->getProfitJournals(), $delta);
            $this->assertEqualsWithDelta(38801851.56363636, $bmcEntity[0]->getProfit(), $delta);
            $this->assertEqualsWithDelta(1940092.5781818181, $bmcEntity[0]->getProfitQuotient(), $delta);

            $this->assertEquals('S', $bmcEntity[0]->getColorGrade());
            $this->assertEqualsWithDelta(1238400.0, $bmcEntity[0]->getFameAmount(), $delta);
            $this->assertEquals(7, $bmcEntity[0]->getTierColor());
            $this->assertEquals(8839840, $bmcEntity[0]->getItemValue());

            $this->assertEquals('3h_axe', $bmcEntity[0]->getItem()->getName());
            $this->assertEquals('metalBar', $bmcEntity[0]->getPrimResource()->getName());
            $this->assertEquals('planks', $bmcEntity[0]->getSecResource()->getName());
            $this->assertEquals('journal_warrior_empty', $bmcEntity[0]->getJournalEntityEmpty()->getName());
            $this->assertEquals('journal_warrior_full', $bmcEntity[0]->getJournalEntityFull()->getName());
        }
    }

    public function getItems(): array
    {
        return [
            (new ItemEntity())
                ->setTier(71)
                ->setName('3h_axe')
                ->setCity('BlackMarket')
                ->setSellOrderPrice(441992)
                ->setBuyOrderPrice(168594)
                ->setWeaponGroup('axe')
                ->setRealName('greatAxe')
                ->setPrimaryResource('metalBar')
                ->setPrimaryResourceAmount(20)
                ->setSecondaryResource('planks')
                ->setSecondaryResourceAmount(12)
                ->refreshFame()
                ->refreshItemValue(),
        ];
    }

    public function getResources(): array
    {
        return [
            (new ResourceEntity())
                ->setTier(71)
                ->setName('metalBar')
                ->setCity('TestCity')
                ->setRealName('metalBar')
                ->setSellOrderPrice(13986)
                ->setBuyOrderPrice(12235),
            (new ResourceEntity())
                ->setTier(71)
                ->setName('planks')
                ->setCity('TestCity')
                ->setRealName('planks')
                ->setSellOrderPrice(13986)
                ->setBuyOrderPrice(12235),
        ];
    }

    public function getJournals(): array
    {
        return [
            (new JournalEntity())
                ->setTier(70)
                ->setName('journal_warrior_empty')
                ->setCity('TestCity')
                ->setSellOrderPrice(9559)
                ->setBuyOrderPrice(9005)
                ->setFameToFill(28380)
                ->setFillStatus('empty'),
            (new JournalEntity())
                ->setTier(70)
                ->setName('journal_warrior_full')
                ->setCity('TestCity')
                ->setSellOrderPrice(9559)
                ->setBuyOrderPrice(9005)
                ->setFameToFill(28380)
                ->setFillStatus('full'),
        ];
    }
}
