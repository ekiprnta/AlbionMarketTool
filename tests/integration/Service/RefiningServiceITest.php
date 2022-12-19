<?php

namespace integration\Service;

use MZierdt\Albion\Entity\RefiningEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\RawResourceRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\RefiningHelper;
use MZierdt\Albion\Service\RefiningService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class RefiningServiceITest extends TestCase
{
    use ProphecyTrait;

    public function testRefiningService(): void
    {
        /** @var ResourceRepository|ObjectProphecy $resourceRepo */
        $resourceRepo = $this->prophesize(ResourceRepository::class);
        /** @var RawResourceRepository|ObjectProphecy $rawResourceRepo */
        $rawResourceRepo = $this->prophesize(RawResourceRepository::class);

        $resourceRepo->getResourcesByBonusCity('TestCity')
            ->willReturn($this->getResources());
        $rawResourceRepo->getRawResourcesByBonusCity('TestCity')
            ->willReturn($this->getRawResources());

        $refiningService = new RefiningService(
            $resourceRepo->reveal(),
            $rawResourceRepo->reveal(),
            new RefiningHelper()
        );

        $delta = 0.00001;
        $testData = $refiningService->getRefiningForCity('TestCity', 500);

        /** @var RefiningEntity $refiningEntity */
        foreach ($testData as $refiningEntity) {
            $this->assertEquals('3', $refiningEntity->getTierColor());
            $this->assertEquals(2, $refiningEntity->getAmountRawResource());
            $this->assertEquals(80204.91, $refiningEntity->getSingleProfit());
            $this->assertEquals(968, $refiningEntity->getAmount());
            $this->assertEqualsWithDelta(77638352.88, $refiningEntity->getProfit(), $delta);
            $this->assertEquals(80204.91, $refiningEntity->getWeightAmountQuotient());
            $this->assertEquals('S', $refiningEntity->getProfitGrade());
        }
    }

    private function getResources(): array
    {
        return [
            new ResourceEntity([
                'bonusCity' => 'Testcity',
                'amountInStorage' => 0,
                'tier' => '3',
                'name' => 'planks',
                'city' => 'TestCity',
                'sellOrderPrice' => 13986,
                'sellOrderPriceDate' => '2022-12-06 21:15:00',
                'buyOrderPrice' => 12235,
                'buyOrderPriceDate' => '2022-12-06 21:15:00',
                'realName' => 'planks',
                'weight' => 1.71,
                'class' => '',
            ]),
            new ResourceEntity(
                [
                    'bonusCity' => 'Testcity',
                    'amountInStorage' => 0,
                    'tier' => '2',
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

    private function getRawResources(): array
    {
        return [
            new ResourceEntity([
                'bonusCity' => 'Testcity',
                'amountInStorage' => 0,
                'tier' => '3',
                'name' => 'planks',
                'city' => 'TestCity',
                'sellOrderPrice' => 1398,
                'sellOrderPriceDate' => '2022-12-06 21:15:00',
                'buyOrderPrice' => 1223,
                'buyOrderPriceDate' => '2022-12-06 21:15:00',
                'realName' => 'planks',
                'weight' => 1.71,
                'class' => '',
            ], true),
        ];
    }
}
