<?php

namespace integration\AlbionMarket;

use MZierdt\Albion\AlbionMarket\BlackMarketTransportingHelper;
use MZierdt\Albion\AlbionMarket\BlackMarketTransportingService;
use MZierdt\Albion\Entity\AdvancedEntitites\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\Service\ConfigService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class BlackMarketTransportingServiceITest extends TestCase
{
    use ProphecyTrait;

    public function testBlackMarketTransportingService(): void
    {
        /** @var ItemRepository|ObjectProphecy $itemRepository */
        $itemRepository = $this->prophesize(ItemRepository::class);

        $itemRepository->getItemsByLocationForBm('TestCity')
            ->willReturn($this->getCityItems());
        $itemRepository->getItemsByLocationForBm('Black Market')
            ->willReturn($this->getBmItems());

        $bmtService = new BlackMarketTransportingService(
            $itemRepository->reveal(),
            new BlackMarketTransportingHelper(),
            new ConfigService()
        );

        $delta = 0.00000001;
        $testData = $bmtService->getDataForCity('TestCity', ['71']);

        /** @var BlackMarketTransportEntity $bmtEntity */
        foreach ($testData as $bmtEntity) {
            $this->assertEquals(20, $bmtEntity->getAmount());
            $this->assertEqualsWithDelta(3425410.4, $bmtEntity->getProfit(), $delta);
            $this->assertEqualsWithDelta(171270.52, $bmtEntity->getSingleProfit(), $delta);
            $this->assertEquals('A', $bmtEntity->getProfitGrade());
            $this->assertEquals(7, $bmtEntity->getTierColor());
            $this->assertEquals(4839840.0, $bmtEntity->getTotalCost());
            $this->assertEquals(182.65, $bmtEntity->getProfitPercentage());

            $this->assertEquals('3h_axe', $bmtEntity->getCityItem()->getName());
            $this->assertEquals('3h_axe', $bmtEntity->getBmItem()->getName());
        }
    }

    public function getBmItems(): array
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
    public function getCityItems(): array
    {
        return [
            (new ItemEntity())
                ->setTier(71)
                ->setName('3h_axe')
                ->setCity('BlackMarket')
                ->setSellOrderPrice(241992)
                ->setBuyOrderPrice(178594)
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
}
