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
            new BlackMarketCraftingHelper()
        );

        $delta = 0.00001;
        $testData = $bmcService->getDataForCity('TestCity', 500, 25.2, 1200, 'TestCity', 1);

        foreach ($testData as $bmcEntity) {
            $this->assertEqualsWithDelta(1.4545454545455, $bmcEntity[0]->getJournalAmountPerItem(), $delta);

            $this->assertEquals(8, $bmcEntity[0]->getTotalAmount());
            $this->assertEquals(160, $bmcEntity[0]->getPrimResourceAmount());
            $this->assertEquals(96, $bmcEntity[0]->getSecResourceAmount());
            $this->assertEqualsWithDelta(11.636363636364, $bmcEntity[0]->getJournalAmount(), $delta);
            $this->assertEqualsWithDelta(182.4, $bmcEntity[0]->getTotalItemWeight(), $delta);

            $this->assertEqualsWithDelta(11059.2, $bmcEntity[0]->getCraftingFee(), $delta);
            $this->assertEqualsWithDelta(439214.54545455, $bmcEntity[0]->getProfitJournals(), $delta);
            $this->assertEqualsWithDelta(1056104.3374545, $bmcEntity[0]->getProfit(), $delta);
            $this->assertEqualsWithDelta(2112.2086749091, $bmcEntity[0]->getWeightProfitQuotient(), $delta);

            $this->assertEquals('S', $bmcEntity[0]->getColorGrade());
            $this->assertEqualsWithDelta(495360.0, $bmcEntity[0]->getFameAmount(), $delta);
            $this->assertEquals('7', $bmcEntity[0]->getTierColor());
            $this->assertEquals(3_535_936, $bmcEntity[0]->getItemValue());

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
            new BlackMarketCraftingHelper()
        );

        $delta = 0.000001;
        $testData = $bmcService->getDataForCity('TestCity', 500, 0, 0, '', 2);

        foreach ($testData as $bmcEntity) {
            $this->assertEqualsWithDelta(1.4545454545455, $bmcEntity[0]->getJournalAmountPerItem(), $delta);

            $this->assertEquals(8, $bmcEntity[0]->getTotalAmount());
            $this->assertEquals(160, $bmcEntity[0]->getPrimResourceAmount());
            $this->assertEquals(96, $bmcEntity[0]->getSecResourceAmount());
            $this->assertEqualsWithDelta(11.636363636364, $bmcEntity[0]->getJournalAmount(), $delta);
            $this->assertEqualsWithDelta(182.4, $bmcEntity[0]->getTotalItemWeight(), $delta);

            $this->assertEqualsWithDelta(0, $bmcEntity[0]->getCraftingFee(), $delta);
            $this->assertEqualsWithDelta(439214.54545455, $bmcEntity[0]->getProfitJournals(), $delta);
            $this->assertEqualsWithDelta(1448814.9934545, $bmcEntity[0]->getProfit(), $delta);
            $this->assertEqualsWithDelta(2897.6299869091, $bmcEntity[0]->getWeightProfitQuotient(), $delta);

            $this->assertEquals('S', $bmcEntity[0]->getColorGrade());
            $this->assertEqualsWithDelta(495360.0, $bmcEntity[0]->getFameAmount(), $delta);
            $this->assertEquals('7', $bmcEntity[0]->getTierColor());
            $this->assertEquals(3_535_936, $bmcEntity[0]->getItemValue());

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
            new ItemEntity([
                'weaponGroup' => 'axe',
                'quality' => 2,
                'primaryResource' => 'metalBar',
                'primaryResourceAmount' => 20,
                'secondaryResource' => 'planks',
                'secondaryResourceAmount' => 12,
                'bonusCity' => 'TestCity',
                'amountInStorage' => null,
                'itemValue' => 8192,
                'fame' => 41280.0,
                'tier' => '71',
                'name' => '3h_axe',
                'city' => 'Black Market',
                'sellOrderPrice' => 441992,
                'sellOrderPriceDate' => '2022-12-06 21:15:00',
                'buyOrderPrice' => 168594,
                'buyOrderPriceDate' => '2022-12-06 21:15:00',
                'realName' => 'greatAxe',
                'weight' => 22.8,
                'class' => 'warrior',
            ]),
        ];
    }

    public function getResources(): array
    {
        return [
            new ResourceEntity([
                'bonusCity' => 'Testcity',
                'amountInStorage' => 0,
                'tier' => '71',
                'name' => 'metalBar',
                'city' => 'TestCity',
                'sellOrderPrice' => 13986,
                'sellOrderPriceDate' => '2022-12-06 21:15:00',
                'buyOrderPrice' => 12235,
                'buyOrderPriceDate' => '2022-12-06 21:15:00',
                'realName' => 'metalBar',
                'weight' => 1.71,
                'class' => '',
            ]),
            new ResourceEntity(
                [
                    'bonusCity' => 'Testcity',
                    'amountInStorage' => 0,
                    'tier' => '71',
                    'name' => 'planks',
                    'city' => 'TestCity',
                    'sellOrderPrice' => 13986,
                    'sellOrderPriceDate' => '2022-12-06 21:15:00',
                    'buyOrderPrice' => 12235,
                    'buyOrderPriceDate' => '2022-12-06 21:15:00',
                    'realName' => 'planks',
                    'weight' => 1.71,
                    'class' => '',
                ]
            ),
        ];
    }

    public function getJournals(): array
    {
        return [
            new JournalEntity([
                'fameToFill' => 28380,
                'fillStatus' => 'empty',
                'tier' => '7',
                'name' => 'journal_warrior_empty',
                'city' => 'Testcity',
                'sellOrderPrice' => 9559,
                'sellOrderPriceDate' => '2022-12-06 21:15:00',
                'buyOrderPrice' => 9005,
                'buyOrderPriceDate' => '2022-12-06 21:15:00',
                'realName' => '',
                'weight' => 1.1,
                'class' => 'warrior',
            ]),
            new JournalEntity([
                'fameToFill' => 28380,
                'fillStatus' => 'full',
                'tier' => '7',
                'name' => 'journal_warrior_full',
                'city' => 'Testcity',
                'sellOrderPrice' => 50000,
                'sellOrderPriceDate' => '2022-12-06 21:15:00',
                'buyOrderPrice' => 43000,
                'buyOrderPriceDate' => '2022-12-06 21:15:00',
                'realName' => '',
                'weight' => 1.1,
                'class' => 'warrior',
            ]),

        ];
    }
}
