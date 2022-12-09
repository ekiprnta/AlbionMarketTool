<?php

namespace unit\Service;

use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\factories\ResourceEntityFactory;
use MZierdt\Albion\Service\BlackMarketCraftingHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class BlackMarketCraftingHelperTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketCraftingHelper $bmcHelper;

    protected function setUp(): void
    {
        $this->bmcHelper = new BlackMarketCraftingHelper();
    }

    /**
     * @dataProvider getResourceData
     */
    public function testCalculateResource(string $entityName, string $entityTier, string $name, string $tier): void
    {
        /** @var ResourceEntity|ObjectProphecy $expectedResourceEntity */
        $expectedResourceEntity = $this->prophesize(ResourceEntity::class);
        $expectedResourceEntity->getTier()
            ->willReturn($entityTier);
        $expectedResourceEntity->getRealName()
            ->willReturn($entityName);

        $this->assertEquals(
            $expectedResourceEntity->reveal(),
            $this->bmcHelper->calculateResource($name, $tier, [$expectedResourceEntity->reveal()])
        );
    }

    public function getResourceData(): array
    {
        return [['r1', '2', 'r1', '2'], ['a bc', '5', 'a bc', '5'], ['t82_item', '82', 't82_item', '82']];
    }

    /**
     * @dataProvider getResourceDataNull
     */
    public function testCalculateResourceNull(string $entityName, string $entityTier, string $name, string $tier): void
    {
        /** @var ResourceEntity|ObjectProphecy $expectedResourceEntity */
        $expectedResourceEntity = $this->prophesize(ResourceEntity::class);
        $expectedResourceEntity->getTier()
            ->willReturn($entityTier);
        $expectedResourceEntity->getRealName()
            ->willReturn($entityName);

        $this->assertEquals(
            ResourceEntityFactory::getEmptyResourceEntity(),
            $this->bmcHelper->calculateResource($name, $tier, [$expectedResourceEntity->reveal()])
        );
    }

    public function getResourceDataNull(): array
    {
        return [['r1', '3', 'r1', '2'], ['a 3', '5', 'a bc', '5'], ['t81_item', '82', 't82_item', '82']];
    }

    /**
     * @dataProvider getJournalDataNull
     */
    public function testCalculateJournalNull(
        string $entityFillStatus,
        string $entityTier,
        string $fillStatus,
        string $tier
    ): void {
        /** @var JournalEntity|ObjectProphecy $expectedJournalEntity */
        $expectedJournalEntity = $this->prophesize(JournalEntity::class);
        $expectedJournalEntity->getTier()
            ->willReturn($entityTier);
        $expectedJournalEntity->getFillStatus()
            ->willReturn($entityFillStatus);

        $this->assertEquals(
            null,
            $this->bmcHelper->calculateJournal($tier, $fillStatus, [$expectedJournalEntity->reveal()])
        );
    }

    public function getJournalDataNull(): array
    {
        return [['full', '3', 'full', '2'], ['full', '51', 'empty', '5'], ['empty', '82', 'full', '82']];
    }

    /**
     * @dataProvider getJournalData
     */
    public function testCalculateJournal(
        string $entityFillStatus,
        string $entityTier,
        string $fillStatus,
        string $tier
    ): void {
        /** @var JournalEntity|ObjectProphecy $expectedJournalEntity */
        $expectedJournalEntity = $this->prophesize(JournalEntity::class);
        $expectedJournalEntity->getTier()
            ->willReturn($entityTier);
        $expectedJournalEntity->getFillStatus()
            ->willReturn($entityFillStatus);

        $this->assertEquals(
            $expectedJournalEntity->reveal(),
            $this->bmcHelper->calculateJournal($tier, $fillStatus, [$expectedJournalEntity->reveal()])
        );
    }

    public function getJournalData(): array
    {
        return [['full', '2', 'full', '2'], ['full', '5', 'full', '5'], ['empty', '8', 'empty', '82']];
    }

    /**
     * @dataProvider getJournalAmountPerItem
     */
    public function testCalculateJournalAmountPerItem(float $fame, int $fameToFill, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcHelper->calculateJournalAmountPerItem($fame, $fameToFill),
            0.00000001
        );
    }

    public function getJournalAmountPerItem(): array
    {
        return [[54, 900, 0.06], [4.5, 1800, 0.0025], [144, 14400, 0.01], [201.6, 28800, 0.007]];
    }

    /**
     * @dataProvider getFameAmount
     */
    public function testCalculateFameAmount(int $totalAmount, float $fame, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcHelper->calculateFameAmount($totalAmount, $fame),
            0.00000001
        );
    }

    public function getFameAmount(): array
    {
        return [[150, 34, 7650.0], [420, 12.5, 7875.0], [6742, 98.4, 995119.2]];
    }

    /**
     * @dataProvider getTotalAmount
     */
    public function testCalculateTotalAmount(
        float $resourceWeight,
        int $resourceAmount,
        float $journalWeight,
        float $journalAmountPerItem,
        float $weight,
        int $expectedResult
    ): void {
        $this->assertEquals(
            $expectedResult,
            $this->bmcHelper->calculateTotalAmount(
                $resourceWeight,
                $resourceAmount,
                $journalWeight,
                $journalAmountPerItem,
                $weight
            )
        );
    }

    public function getTotalAmount(): array
    {
        return [[6.3, 32, 0.3, 0.025, 2000, 9], [17.3, 8, 0.8, 0.25, 2000, 14], [76, 988, 4, 23, 400032, 5]];
    }

    /**
     * @dataProvider getResourceAmount
     */
    public function testCalculateResourceAmount(int $totalAmount, int $resourceAmount, int $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcHelper->calculateResourceAmount($totalAmount, $resourceAmount),
            0.00000001
        );
    }

    public function getResourceAmount(): array
    {
        return [[150, 34, 5100], [3, 7, 21], [14, 32, 448]];
    }

    /**
     * @dataProvider getJournalAmount
     */
    public function testCalculateJournalAmount(int $totalAmount, float $journalAmountPerItem, int $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcHelper->calculateJournalAmount($totalAmount, $journalAmountPerItem),
            0.00000001
        );
    }

    public function getJournalAmount(): array
    {
        return [[150, 34, 5100], [3, 7, 21], [2345, 0.025, 59]];
    }

    /**
     * @dataProvider getTotalItemWeight
     */
    public function testCalculateTotalItemWeight(int $totalAmount, float $weight, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcHelper->calculateTotalItemWeight($totalAmount, $weight),
            0.00000001
        );
    }

    public function getTotalItemWeight(): array
    {
        return [[150, 34, 5100], [3, 7, 21], [2345, 0.025, 58.625]];
    }

    /**
     * @dataProvider getCraftingFee
     */
    public function testCalculateCraftingFee(int $itemValue, int $feeProHundredNutrition, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcHelper->calculateCraftingFee($itemValue, $feeProHundredNutrition),
            0.00000001
        );
    }

    public function getCraftingFee(): array
    {
        return [[150, 400, 67.5], [3, 1800, 6.075], [2345, 4100, 10816.3125]];
    }

    /**
     * @dataProvider getProfit
     */
    public function testCalculateProfit(
        int $totalAmount,
        int $itemPrice,
        float $itemCost,
        float $percentage,
        float $craftingFee,
        float $profitJournals,
        float $expectedResult
    ): void {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcHelper->calculateProfit(
                $totalAmount,
                $itemPrice,
                $itemCost,
                $percentage,
                $craftingFee,
                $profitJournals
            ),
            0.00000001
        );
    }

    public function getProfit(): array
    {
        return [
            [150, 62399, 5610, 24.8, 400, 40200, 8158451.749999999],
            [2345, 987654, 345345, 50.3, 1800, 9654, 1763025812.6249998],
            [65, 333, 5610, 24.8, 4100, 300000, 41921.274999999994],
        ];
    }

    /**
     * @dataProvider getProfitJournals
     */
    public function testCalculateProfitJournals(
        int $emptyJournalPrice,
        int $fullJournalPrice,
        int $journalAmount,
        float $expectedResult
    ): void {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcHelper->calculateProfitJournals($emptyJournalPrice, $fullJournalPrice, $journalAmount,),
            0.00000001
        );
    }

    public function getProfitJournals(): array
    {
        return [[9876, 62398, 40, 1938645.2], [98765, 34567, 7, -465113.985], [157650, 2344, 365, -56742301.4]];
    }

    /**
     * @dataProvider getItemValue
     */
    public function testCalculateItemValue(int $totalAmount, int $price, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcHelper->calculateItemValue($totalAmount, $price,),
            0.00000001
        );
    }

    public function getItemValue(): array
    {
        return [[150, 62398, 9359700], [3, 7, 21], [23456, 234, 5488704]];
    }

    /**
     * @dataProvider getBuyOrderItemCost
     */
    public function testCalculateBuyOrderItemCost(
        int $primResourcePrice,
        int $primResourceAmount,
        int $secResourcePrice,
        int $secResourceAmount,
        float $expectedResult
    ): void {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcHelper->calculateBuyOrderItemCost(
                $primResourcePrice,
                $primResourceAmount,
                $secResourcePrice,
                $secResourceAmount
            ),
            0.00000001
        );
    }

    public function getBuyOrderItemCost(): array
    {
        return [[42000, 16, 44300, 8, 1000740.0], [1343, 12, 65, 20, 16980.6], [2345, 8, 3456, 6, 38508.6]];
    }
    /**
     * @dataProvider getSellOrderItemCost
     */
    public function testCalculateSellOrderItemCost(
        int $primResourcePrice,
        int $primResourceAmount,
        int $secResourcePrice,
        int $secResourceAmount,
        float $expectedResult
    ): void {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcHelper->calculateSellOrderItemCost(
                $primResourcePrice,
                $primResourceAmount,
                $secResourcePrice,
                $secResourceAmount
            ),
            0.00000001
        );
    }

    public function getSellOrderItemCost(): array
    {
        return [[42000, 16, 44300, 8, 1026400.0], [1343, 12, 65, 20, 17416.0], [2345, 8, 3456, 6, 39496.0]];
    }
}
