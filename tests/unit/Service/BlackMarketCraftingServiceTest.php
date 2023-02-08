<?php

namespace unit\Service;

use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\JournalRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\BlackMarketCraftingHelper;
use MZierdt\Albion\Service\BlackMarketCraftingService;
use MZierdt\Albion\Service\ConfigService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class BlackMarketCraftingServiceTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketCraftingService $bmcService;
    private ObjectProphecy|ItemRepository $itemRepository;
    private ObjectProphecy|ResourceRepository $resourceRepository;
    private ObjectProphecy|JournalRepository $journalRepository;
    private ObjectProphecy|BlackMarketCraftingHelper $bmcHelper;
    private ObjectProphecy|ConfigService $configService;

    protected function setUp(): void
    {
        $this->itemRepository = $this->prophesize(ItemRepository::class);
        $this->resourceRepository = $this->prophesize(ResourceRepository::class);
        $this->journalRepository = $this->prophesize(JournalRepository::class);
        $this->bmcHelper = $this->prophesize(BlackMarketCraftingHelper::class);
        $this->configService = $this->prophesize(ConfigService::class);

        $this->bmcService = new BlackMarketCraftingService(
            $this->itemRepository->reveal(),
            $this->resourceRepository->reveal(),
            $this->journalRepository->reveal(),
            $this->bmcHelper->reveal(),
            $this->configService->reveal(),
        );
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
     * @dataProvider getExceptionValues
     */
    public function testGetDataForCityException(string $itemCity): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->bmcService->getDataForCity($itemCity, 0.0, 0, '', '');
    }

    public function getExceptionValues(): array
    {
        return [[''], ['']];
    }
//
//    /** @dataProvider getValuesForCityData */
//    public function testAGetDataForCity(
//        string $itemCity,
//        int $weight,
//        float $percentage,
//        int $feeProHundredNutrition,
//        string $resourceCity,
//        string $order,
//        array $expectedResult,
//    ): void {
//        /** @var ItemEntity|ObjectProphecy $itemEntity */
//        $itemEntity = $this->prophesize(ItemEntity::class);
//        $itemEntity->getTier()->willReturn('4');
//        $itemEntity->getPrimaryResource()->willReturn('metalBar');
//
//        /** @var ResourceEntity|ObjectProphecy $primResourceEntity */
//        $primResourceEntity = $this->prophesize(ResourceEntity::class);
//        $primResourceEntity->getTier()->willReturn('4');
//        $primResourceEntity->getRealName()->willReturn('metalBar');
//
//        /** @var BlackMarketCraftingEntity|ObjectProphecy $bmcEntity */
//        $bmcEntity = $this->prophesize(BlackMarketCraftingEntity::class);
//        $bmcEntity->getItem()->getPrimaryResource()->willReturn('metalBar');
//        $bmcEntity->reveal();
//        $bmcEntity->getItem()->getTier()->willReturn('4');
//
//        $itemEntity = new ItemEntity([
//            "tier" => "2",
//            "name" => "2h_divinestaff",
//            "weaponGroup" => "holyStaff",
//            "realName" => "divineStaff",
//            "class" => "mage",
//            "city" => "Black Market",
//            "quality" => "2",
//            "sellOrderPrice" => 10000,
//            "sellOrderPriceDate" => '2000-01-01T12:12:12',
//            "buyOrderPrice" => 5000,
//            "buyOrderPriceDate" => '2000-01-01T12:12:12',
//            "primaryResource" => "planks",
//            "primaryResourceAmount" => "20",
//            "secondaryResource" => "cloth",
//            "secondaryResourceAmount" => "12",
//            "bonusCity" => "Fort Sterling",
//            "fameFactor" => null,
//            "amountInStorage" => null,
//        ]);
//
//        $primResourceEntity = new ResourceEntity([
//            "tier" => "2",
//            "name" => "cloth",
//            "city" => "Fort Sterling",
//            "realName" => "cloth",
//            "sellOrderPrice" => "27",
//            "sellOrderPriceDate" => "2022-12-08 10:45:00",
//            "buyOrderPrice" => "26",
//            "buyOrderPriceDate" => "2022-12-08 10:45:00",
//            "bonusCity" => "Lymhurst",
//            "amountInStorage" => null,
//        ]);
//        $secResourceEntity = new ResourceEntity([
//            "tier" => "2",
//            "name" => "planks",
//            "city" => "Fort Sterling",
//            "realName" => "planks",
//            "sellOrderPrice" => "27",
//            "sellOrderPriceDate" => "2022-12-08 10:45:00",
//            "buyOrderPrice" => "26",
//            "buyOrderPriceDate" => "2022-12-08 10:45:00",
//            "bonusCity" => "Lymhurst",
//            "amountInStorage" => null,
//        ]);
//
//        $journalFull = new JournalEntity([
//            "tier" => "2",
//            "name" => "journal_mage_empty",
//            "city" => "Fort Sterling",
//            "fameToFill" => "900",
//            "sellOrderPrice" => "25",
//            "sellOrderPriceDate" => "0001-01-01 00:00:00",
//            "buyOrderPrice" => "6",
//            "buyOrderPriceDate" => "2022-12-01 21:20:00",
//            "weight" => "0.2",
//            "fillStatus" => "full",
//            "class" => "mage",
//        ]);
//        $journalEmpty = new JournalEntity([
//            "tier" => "2",
//            "name" => "journal_mage_empty",
//            "city" => "Fort Sterling",
//            "fameToFill" => "900",
//            "sellOrderPrice" => "12",
//            "sellOrderPriceDate" => "0001-01-01 00:00:00",
//            "buyOrderPrice" => "6",
//            "buyOrderPriceDate" => "2022-12-01 21:20:00",
//            "weight" => "0.2",
//            "fillStatus" => "empty",
//            "class" => "mage",
//        ]);
//
//
//        $this->itemRepository->getBlackMarketItemsFromCity($itemCity)->willReturn([$itemEntity]);
//        $this->resourceRepository->getResourcesByCity($itemCity)->willReturn([$primResourceEntity, $secResourceEntity]);
//        $this->journalRepository->getJournalsFromCity($itemCity)->willReturn([[$journalEmpty, $journalFull]]);
//
//
//        $result = $this->bmcService->getDataForCity(
//            $itemCity,
//            $weight,
//            $percentage,
//            $feeProHundredNutrition,
//            $resourceCity,
//            $order
//        );
//        $this->assertEquals($expectedResult, $result);
//    }
//
//    public function getValuesForCityData(): array
//    {
//        return [
//            ['Bridgewatch', 2700, 24.8, 425, '', '2', []]
//        ];
//    }
}
