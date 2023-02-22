<?php

namespace integration\AlbionMarket;

use MZierdt\Albion\AlbionMarket\BlackMarketCraftingHelper;
use MZierdt\Albion\AlbionMarket\BlackMarketCraftingService;
use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\JournalRepository;
use MZierdt\Albion\repositories\ResourceRepository;
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
            /** @var BlackMarketCraftingEntity $blackMarketCraftingEntity */
            $blackMarketCraftingEntity = $bmcEntity[0];
            $this->assertEqualsWithDelta(
                1.4545454545455,
                $blackMarketCraftingEntity->getJournalAmountPerItem(),
                $delta
            );

            $this->assertEquals(20, $blackMarketCraftingEntity->getTotalAmount());
            $this->assertEquals(400, $blackMarketCraftingEntity->getPrimResourceTotalAmount());
            $this->assertEquals(240, $blackMarketCraftingEntity->getSecResourceTotalAmount());
            $this->assertEqualsWithDelta(
                29.090909090909093,
                $blackMarketCraftingEntity->getJournalTotalAmount(),
                $delta
            );

            $this->assertEqualsWithDelta(11059.2, $blackMarketCraftingEntity->getCraftingFee(), $delta);
            $this->assertEqualsWithDelta(-1958.8363636364, $blackMarketCraftingEntity->getProfitJournals(), $delta);
            $this->assertEqualsWithDelta(1556854.4436363627, $blackMarketCraftingEntity->getProfit(), $delta);
            $this->assertEqualsWithDelta(98.76, $blackMarketCraftingEntity->getProfitPercentage(), $delta);

            $this->assertEquals('D', $blackMarketCraftingEntity->getColorGrade());
            $this->assertEqualsWithDelta(1238400.0, $blackMarketCraftingEntity->getFameAmount(), $delta);
            $this->assertEquals(7, $blackMarketCraftingEntity->getTierColor());
            $this->assertEquals(8839840, $blackMarketCraftingEntity->getItemValue());

            $this->assertEquals('3h_axe', $blackMarketCraftingEntity->getItem()->getName());
            $this->assertEquals('metalBar', $blackMarketCraftingEntity->getPrimResource()->getName());
            $this->assertEquals('planks', $blackMarketCraftingEntity->getSecResource()->getName());
            $this->assertEquals(
                'journal_warrior_empty',
                $blackMarketCraftingEntity->getJournalEntityEmpty()
                    ->getName()
            );
            $this->assertEquals('journal_warrior_full', $blackMarketCraftingEntity->getJournalEntityFull()->getName());
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
        $testData = $bmcService->getDataForCity('TestCity', 0.0, 0, 0, '', 2);

        foreach ($testData as $bmcEntity) {
            /** @var BlackMarketCraftingEntity $blackMarketCraftingEntity */
            $blackMarketCraftingEntity = $bmcEntity[0];
            $this->assertEqualsWithDelta(
                1.4545454545455,
                $blackMarketCraftingEntity->getJournalAmountPerItem(),
                $delta
            );

            $this->assertEquals(20, $blackMarketCraftingEntity->getTotalAmount());
            $this->assertEquals(400, $blackMarketCraftingEntity->getPrimResourceTotalAmount());
            $this->assertEquals(240, $blackMarketCraftingEntity->getSecResourceTotalAmount());
            $this->assertEqualsWithDelta(
                29.090909090909093,
                $blackMarketCraftingEntity->getJournalTotalAmount(),
                $delta
            );

            $this->assertEqualsWithDelta(0, $blackMarketCraftingEntity->getCraftingFee(), $delta);
            $this->assertEqualsWithDelta(-1958.8363636363913, $blackMarketCraftingEntity->getProfitJournals(), $delta);
            $this->assertEqualsWithDelta(2522042.283636363, $blackMarketCraftingEntity->getProfit(), $delta);
            $this->assertEqualsWithDelta(115.79, $blackMarketCraftingEntity->getProfitPercentage(), $delta);

            $this->assertEquals('C', $blackMarketCraftingEntity->getColorGrade());
            $this->assertEqualsWithDelta(1238400.0, $blackMarketCraftingEntity->getFameAmount(), $delta);
            $this->assertEquals(7, $blackMarketCraftingEntity->getTierColor());
            $this->assertEquals(8839840, $blackMarketCraftingEntity->getItemValue());

            $this->assertEquals('3h_axe', $blackMarketCraftingEntity->getItem()->getName());
            $this->assertEquals('metalBar', $blackMarketCraftingEntity->getPrimResource()->getName());
            $this->assertEquals('planks', $blackMarketCraftingEntity->getSecResource()->getName());
            $this->assertEquals(
                'journal_warrior_empty',
                $blackMarketCraftingEntity->getJournalEntityEmpty()
                    ->getName()
            );
            $this->assertEquals('journal_warrior_full', $blackMarketCraftingEntity->getJournalEntityFull()->getName());
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
