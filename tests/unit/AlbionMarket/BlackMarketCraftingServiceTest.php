<?php

namespace unit\AlbionMarket;

use MZierdt\Albion\AlbionMarket\BlackMarketCraftingService;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\factories\ResourceEntityFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class BlackMarketCraftingServiceTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketCraftingService $bmcService;

    protected function setUp(): void
    {
        $this->bmcService = new BlackMarketCraftingService();
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
            $this->bmcService->calculateResource($name, $tier, [$expectedResourceEntity->reveal()])
        );
    }

    public function getResourceData(): array
    {
        return [['r1', '2', 'r1', '2'], ['a bc', '5', 'a bc', '5'], ['t82_item', '82', 't82_item', '82']];
    }

    /**
     * @dataProvider getResourceDataNull
     */
    public function testCalculateResourceNull(string $entityName, string $entityTier, string $name, int $tier): void
    {
        /** @var ResourceEntity|ObjectProphecy $expectedResourceEntity */
        $expectedResourceEntity = $this->prophesize(ResourceEntity::class);
        $expectedResourceEntity->getTier()
            ->willReturn($entityTier);
        $expectedResourceEntity->getRealName()
            ->willReturn($entityName);

        $this->assertEquals(
            ResourceEntityFactory::getEmptyResourceEntity(),
            $this->bmcService->calculateResource($name, $tier, [$expectedResourceEntity->reveal()])
        );
    }

    public function getResourceDataNull(): array
    {
        return [['r1', '3', 'r1', 20], ['a 3', '5', 'a bc', 50], ['t81_item', '82', 't82_item', 82]];
    }

    /**
     * @dataProvider getJournalDataNull
     */
    public function testCalculateJournalNull(
        string $entityFillStatus,
        string $entityTier,
        string $fillStatus,
        int $tier
    ): void {
        /** @var JournalEntity|ObjectProphecy $expectedJournalEntity */
        $expectedJournalEntity = $this->prophesize(JournalEntity::class);
        $expectedJournalEntity->getTier()
            ->willReturn($entityTier);
        $expectedJournalEntity->getFillStatus()
            ->willReturn($entityFillStatus);

        $this->assertEquals(
            null,
            $this->bmcService->calculateJournal($tier, $fillStatus, [$expectedJournalEntity->reveal()])
        );
    }

    public function getJournalDataNull(): array
    {
        return [['full', '3', 'full', 20], ['full', '51', 'empty', 50], ['empty', '82', 'full', 82]];
    }

    /**
     * @dataProvider getJournalData
     */
    public function testCalculateJournal(
        string $entityFillStatus,
        int $entityTier,
        string $fillStatus,
        int $tier
    ): void {
        $journalEntity = (new JournalEntity())
            ->setTier($entityTier)
            ->setFillStatus($entityFillStatus);

        $this->assertEquals(
            $journalEntity,
            $this->bmcService->calculateJournal($tier, $fillStatus, [$journalEntity])
        );
    }

    public function getJournalData(): array
    {
        return [['full', 20, 'full', 20], ['full', 50, 'full', 50], ['empty', 80, 'empty', 82]];
    }

    /**
     * @dataProvider getJournalAmountPerItem
     */
    public function testCalculateJournalAmountPerItem(float $fame, int $fameToFill, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcService->calculateJournalAmountPerItem($fame, $fameToFill),
            0.00000001
        );
    }

    public function getJournalAmountPerItem(): array
    {
        return [[54, 900, 0.06], [4.5, 1800, 0.0], [144, 14400, 0.01], [201.6, 28800, 0.01]];
    }

    /**
     * @dataProvider getFameAmount
     */
    public function testCalculateFameAmount(int $totalAmount, float $fame, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcService->calculateFameAmount($totalAmount, $fame),
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
    public function testCalculateTotalAmount(int $expectedResult, int $tier, int $totalAmount): void
    {
        $bmSells = [
            '20' => ['32' => 2000],
            '61' => ['16' => 750],
            '83' => ['8' => 1],
        ];

        $this->assertEquals(
            $expectedResult,
            $this->bmcService->calculateTotalAmount($tier, $totalAmount, $bmSells)
        );
    }

    public function getTotalAmount(): array
    {
        return [[2000, 20, 32], [750, 61, 16], [1, 83, 8]];
    }

    /**
     * @dataProvider getResourceAmount
     */
    public function testCalculateResourceAmount(int $totalAmount, int $resourceAmount, int $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcService->calculateResourceAmount($totalAmount, $resourceAmount),
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
    public function testCalculateJournalAmount(
        int $totalAmount,
        float $journalAmountPerItem,
        float $expectedResult
    ): void {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcService->calculateJournalAmount($totalAmount, $journalAmountPerItem),
            0.00000001
        );
    }

    public function getJournalAmount(): array
    {
        return [[150, 34, 5100], [3, 7, 21], [2345, 0.025, 58.625]];
    }

    /**
     * @dataProvider getTotalItemWeight
     */
    public function testCalculateTotalItemWeight(int $totalAmount, float $weight, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcService->calculateTotalItemWeight($totalAmount, $weight),
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
            $this->bmcService->calculateCraftingFee($itemValue, $feeProHundredNutrition),
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
            $this->bmcService->calculateProfit(
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
            [150, 62399, 5610, 24.8, 400, 40200, -67868.75],
            [2345, 987654, 345345, 50.3, 1800, 9654, -1330806.425],
            [65, 333, 5610, 24.8, 4100, 300000, -5882.225],
            [1, 162998, 310656, 47.9, 500, 40200, -473653.065],
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
            $this->bmcService->calculateProfitJournals($fullJournalPrice, $emptyJournalPrice, $journalAmount,),
            0.00000001
        );
    }

    public function getProfitJournals(): array
    {
        return [[9876, 62398, 40, 1_938_645.2], [98765, 34567, 7, -465113.985], [157650, 2344, 365, -56_742_301.4]];
    }

    /**
     * @dataProvider getItemValue
     */
    public function testCalculateItemValue(int $totalAmount, int $price, float $expectedResult): void
    {
        $this->assertEqualsWithDelta(
            $expectedResult,
            $this->bmcService->calculateItemValue($totalAmount, $price,),
            0.00000001
        );
    }

    public function getItemValue(): array
    {
        return [[150, 62398, 9_359_700], [3, 7, 21], [23456, 234, 5_488_704]];
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
            $this->bmcService->calculateBuyOrderItemCost(
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
        return [[42000, 16, 44300, 8, 1_000_740.0], [1343, 12, 65, 20, 16980.6], [2345, 8, 3456, 6, 38508.6]];
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
            $this->bmcService->calculateSellOrderItemCost(
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
        return [[42000, 16, 44300, 8, 1_026_400.0], [1343, 12, 65, 20, 17416.0], [2345, 8, 3456, 6, 39496.0]];
    }

    public function testGetCraftingRates(): void
    {
        $this->assertEquals([
            'No City Bonus No Focus' => 15.2,
            'No City Bonus Focus' => 43.5,
            'City Bonus No Focus' => 24.8,
            'City Bonus Focus' => 47.9,
        ], $this->bmcService->getCraftingRates());
    }

    /**
     * @dataProvider provideMaterials
     */
    public function testCalculateMaterialCost(
        float $result,
        float $resourceCost,
        float $journalPrice,
        float $journalAmountPerItem,
        float $percentage
    ): void {
        $this->assertEqualsWithDelta(
            $result,
            $this->bmcService->calculateMaterialCost($resourceCost, $journalPrice, $journalAmountPerItem, $percentage),
            0.0001
        );
    }

    public function provideMaterials(): array
    {
        return [[36050, 50000, 10000, 1, 47.9], [287475.23, 532180, 7041, 1.45, 47.9]];
    }
}
