<?php

namespace integration\Service;

use MZierdt\Albion\Entity\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\Service\BlackMarketTransportingHelper;
use MZierdt\Albion\Service\BlackMarketTransportingService;
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

        $itemRepository->getItemsForTransport('TestCity')
            ->willReturn($this->getCityItems());
        $itemRepository->getItemsForTransport('Black Market')
            ->willReturn($this->getBmItems());

        $bmtService = new BlackMarketTransportingService(
            $itemRepository->reveal(),
            new BlackMarketTransportingHelper()
        );

        $delta = 0.00000001;
        $testData = $bmtService->getDataForCity('TestCity', 500, ['71']);

        /** @var BlackMarketTransportEntity $bmtEntity */
        foreach ($testData as $bmtEntity) {
            $this->assertEquals(21, $bmtEntity->getAmount());
            $this->assertEqualsWithDelta(3596680.92, $bmtEntity->getProfit(), $delta);
            $this->assertEqualsWithDelta(171270.52, $bmtEntity->getSingleProfit(), $delta);
            $this->assertEqualsWithDelta(7193.36184, $bmtEntity->getWeightProfitQuotient(), $delta);
            $this->assertEquals('S', $bmtEntity->getProfitGrade());
            $this->assertEquals('7', $bmtEntity->getTierColor());
            $this->assertEquals(500, $bmtEntity->getWeight());

            $this->assertEquals('3h_axe', $bmtEntity->getCityItem()->getName());
            $this->assertEquals('3h_axe', $bmtEntity->getBmItem()->getName());
        }
    }

    public function getBmItems(): array
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
    public function getCityItems(): array
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
                'city' => 'TestCity',
                'sellOrderPrice' => 241992,
                'sellOrderPriceDate' => '2022-12-06 21:15:00',
                'buyOrderPrice' => 178594,
                'buyOrderPriceDate' => '2022-12-06 21:15:00',
                'realName' => 'greatAxe',
                'weight' => 22.8,
                'class' => 'warrior',
            ]),
        ];
    }
}
