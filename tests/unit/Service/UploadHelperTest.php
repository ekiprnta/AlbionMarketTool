<?php

namespace unit\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\Service\TierService;
use MZierdt\Albion\Service\UploadHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class UploadHelperTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|TierService $tierService;
    private UploadHelper $uploadHelper;

    /**
     * @dataProvider provideResources
     */
    public function testAdjustResources(array $result, array $resourceData): void
    {
        $resourceStats = [
            'realName' => 'metalBar',
            'bonusCity' => 'Thetford',
        ];

        $this->tierService->splitIntoTierAndName(Argument::any())->willReturn(
            [
                'tier' => '71',
                'name' => 'METALBAR_LEVEL1',
            ]
        );

        $this->assertEquals($result, $this->uploadHelper->adjustResources([$resourceData], $resourceStats));
    }

    public function provideResources(): array
    {
        $resourceA = (new ResourceEntity())
            ->setTier(71)
            ->setName('metalbar')
            ->setCity('Martlock')
            ->calculateSellOrderAge('2000-00-00T12:00:00')
            ->setSellOrderPrice(13986)
            ->calculateBuyOrderAge('2000-00-00T12:00:00')
            ->setBuyOrderPrice(12235)
            ->setClass('metalBar')
            ->setRealName('metalBar')
            ->setBonusCity('Thetford');

        $resourceData = [
            'item_id' => 'T7_metalbar_level1@1',
            'city' => 'Martlock',
            'sell_price_min_date' => '2000-00-00T12:00:00',
            'sell_price_min' => 13986,
            'buy_price_max_date' => '2000-00-00T12:00:00',
            'buy_price_max' => 12235,
        ];
        return [[[$resourceA], $resourceData]];
    }

    /**
     * @dataProvider provideJournals
     */
    public function testAdjustJournals(array $result, array $journalData): void
    {
        $journalStats = [
            '70' => ['fameToFill' => 28380],
            '40' => ['fameToFill' => 50],
        ];

        $this->tierService->splitIntoTierAndName(Argument::any())->willReturn(
            ['tier' => '70', 'name' => 'journal_warrior_empty']
        );
        $this->tierService->journalSplitter(Argument::any())->willReturn(
            ['class' => 'warrior', 'fillStatus' => 'empty']
        );

        $this->assertEquals($result, $this->uploadHelper->adjustJournals([$journalData], $journalStats));
    }

    public function provideJournals(): array
    {
        $journalA = (new JournalEntity())
            ->setTier(70)
            ->setName('journal_warrior_empty')
            ->setCity('Martlock')
            ->setSellOrderPrice(9559)
            ->calculateSellOrderAge('2000-00-00T12:00:00')
            ->setBuyOrderPrice(9005)
            ->calculateBuyOrderAge('2000-00-00T12:00:00')
            ->setRealName('warrior')
            ->setFameToFill(28380)
            ->setClass('warrior')
            ->setFillStatus('empty');

        $journalData = [
            'item_id' => 'T7_JOURNAL_WARRIOR_EMPTY',
            'city' => 'Martlock',
            'sell_price_min_date' => '2000-00-00T12:00:00',
            'sell_price_min' => 9559,
            'buy_price_max_date' => '2000-00-00T12:00:00',
            'buy_price_max' => 9005,
        ];
        return [[[$journalA], $journalData]];
    }

    /**
     * @dataProvider provideItems
     */
    public function testAdjustItems(array $result, array $itemData): void
    {
        $itemStats = [
            'class' => 'warrior',
            'weaponGroup' => 'axe',
            'realName' => 'greatAxe',
            'primaryResource' => 'metalBar',
            'primaryResourceAmount' => 20,
            'secondaryResource' => 'planks',
            'secondaryResourceAmount' => 12,
            'bonusCity' => 'Martlock',
            'artifact' => null
        ];

        $this->tierService->splitIntoTierAndName(Argument::any())->willReturn(
            ['tier' => '71', 'name' => '2h_axe']
        );

        $this->assertEquals($result, $this->uploadHelper->adjustItems([$itemData], $itemStats, true));
    }

    public function provideItems(): array
    {
        $itemA = (new ItemEntity())
            ->setTier(71)
            ->setName('2h_axe')
            ->setCity('Black Market')
            ->setSellOrderPrice(441992)
            ->calculateSellOrderAge('2000-00-00T12:00:00')
            ->setBuyOrderPrice(168594)
            ->calculateBuyOrderAge('2000-00-00T12:00:00')
            ->setRealName('greatAxe')
            ->setClass('warrior')
            ->setQuality(2)
            ->setWeaponGroup('axe')
            ->setBonusCity('Martlock')
            ->setPrimaryResource('metalBar')
            ->setPrimaryResourceAmount(20)
            ->setSecondaryResource('planks')
            ->setSecondaryResourceAmount(12)
            ->setBlackMarketSellable(true)
            ->refreshFame()
            ->refreshItemValue();

        $itemData = [
            'item_id' => 'T7_2H_AXE@1',
            'city' => 'Black Market',
            'sell_price_min_date' => '2000-00-00T12:00:00',
            'sell_price_min' => 441992,
            'buy_price_max_date' => '2000-00-00T12:00:00',
            'buy_price_max' => 168594,
            'quality' => 2,
        ];
        return [[[$itemA], $itemData]];
    }

    /**
     * @dataProvider provideMaterials
     */
    public function testAdjustMaterials(array $result, array $itemData): void
    {
        $this->tierService->splitIntoTierAndName(Argument::any())->willReturn(['tier' => '40', 'name' => 'rune']);

        $this->assertEquals($result, $this->uploadHelper->adjustMaterials([$itemData], 'materials'));
    }

    public function provideMaterials(): array
    {
        $itemA = (new MaterialEntity())
            ->setTier(40)
            ->setName('rune')
            ->setCity('Martlock')
            ->setSellOrderPrice(25)
            ->calculateSellOrderAge('2000-00-00T12:00:00')
            ->setBuyOrderPrice(23)
            ->calculateBuyOrderAge('2000-00-00T12:00:00')
            ->setType('materials')
            ->setRealName('rune');

        $itemData = [
            'item_id' => 'T4_rune',
            'city' => 'Martlock',
            'sell_price_min_date' => '2000-00-00T12:00:00',
            'sell_price_min' => 25,
            'buy_price_max_date' => '2000-00-00T12:00:00',
            'buy_price_max' => 23,
        ];
        return [[[$itemA], $itemData]];
    }

    /**
     * @dataProvider provideResourceNames
     */
    public function testGetResourceName(string $name): void
    {
        $this->assertEquals('metalbar', $this->uploadHelper->getResourceName($name));
    }

    public function provideResourceNames(): array
    {
        return [['METALBAR'], ['METALBAR_LEVEL1'], ['METALBAR_LEVEL2'], ['metalbar_level3']];
    }

    protected function setUp(): void
    {
        $this->tierService = $this->prophesize(TierService::class);

        $this->uploadHelper = new UploadHelper($this->tierService->reveal(),);
    }
}
