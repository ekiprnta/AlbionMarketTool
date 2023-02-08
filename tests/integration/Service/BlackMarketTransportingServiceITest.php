<?php

namespace integration\Service;

use MZierdt\Albion\Entity\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\Service\BlackMarketTransportingHelper;
use MZierdt\Albion\Service\BlackMarketTransportingService;
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

        $itemRepository->getItemsByLocation('TestCity')
            ->willReturn($this->getCityItems());
        $itemRepository->getItemsByLocation('Black Market')
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
            $this->assertEqualsWithDelta(171270.52, $bmtEntity->getProfitQuotient(), $delta);
            $this->assertEquals('S', $bmtEntity->getProfitGrade());
            $this->assertEquals(7, $bmtEntity->getTierColor());
            $this->assertEquals(4839840.0, $bmtEntity->getTotalCost());
            $this->assertEquals(182.6466054803238, $bmtEntity->getProfitPercentage());

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
