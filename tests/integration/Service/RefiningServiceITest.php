<?php

namespace integration\Service;

use MZierdt\Albion\Entity\RefiningEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\RefiningHelper;
use MZierdt\Albion\Service\RefiningService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class RefiningServiceITest extends TestCase
{
    use ProphecyTrait;

    public function testRefiningServiceA(): void
    {
        /** @var ResourceRepository|ObjectProphecy $resourceRepo */
        $resourceRepo = $this->prophesize(ResourceRepository::class);

        $resourceRepo->getResourcesByBonusCity('TestCity')
            ->willReturn($this->getResources());
        $resourceRepo->getRawResourcesByBonusCity('TestCity')
            ->willReturn($this->getRawResources());

        $refiningService = new RefiningService($resourceRepo->reveal(), new RefiningHelper());

        $delta = 0.00001;
        $testData = $refiningService->getRefiningForCity('TestCity', 500);

        /** @var RefiningEntity $refiningEntity */
        foreach ($testData as $refiningEntity) {
            $this->assertEquals(3, $refiningEntity->getTierColor());
            $this->assertEquals(2, $refiningEntity->getAmountRawResource());
            $this->assertEquals(80204.91, $refiningEntity->getSingleProfit());
            $this->assertEquals(968, $refiningEntity->getAmount());
            $this->assertEqualsWithDelta(77_638_352.88, $refiningEntity->getProfit(), $delta);
            $this->assertEquals(80204.91, $refiningEntity->getProfitQuotient());
            $this->assertEquals('S', $refiningEntity->getProfitGrade());
        }
    }

    public function testRefiningServiceB(): void
    {
        /** @var ResourceRepository|ObjectProphecy $resourceRepo */
        $resourceRepo = $this->prophesize(ResourceRepository::class);

        $resourceRepo->getResourcesByBonusCity('TestCity')
            ->willReturn($this->getResources());
        $resourceRepo->getRawResourcesByBonusCity('TestCity')
            ->willReturn($this->getRawResources());

        $refiningService = new RefiningService($resourceRepo->reveal(), new RefiningHelper());

        $delta = 0.00001;
        $testData = $refiningService->getRefiningForCity('TestCity', 0);

        /** @var RefiningEntity $refiningEntity */
        foreach ($testData as $refiningEntity) {
            $this->assertEquals(3, $refiningEntity->getTierColor());
            $this->assertEquals(2, $refiningEntity->getAmountRawResource());
            $this->assertEqualsWithDelta(5340.408, $refiningEntity->getSingleProfit(), $delta);
            $this->assertEquals(968, $refiningEntity->getAmount());
            $this->assertEqualsWithDelta(5169514.943999999, $refiningEntity->getProfit(), $delta);
            $this->assertEqualsWithDelta(5340.408, $refiningEntity->getProfitQuotient(), $delta);
            $this->assertEquals('S', $refiningEntity->getProfitGrade());
        }
    }

    private function getResources(): array
    {
        return [
            (new ResourceEntity())
                ->setTier(30)
                ->setName('planks')
                ->setCity('TestCity')
                ->setRealName('planks')
                ->setSellOrderPrice(13986)
                ->setBuyOrderPrice(12235)
                ->setRaw(false),
            (new ResourceEntity())
                ->setTier(20)
                ->setName('planks')
                ->setCity('TestCity')
                ->setRealName('planks')
                ->setSellOrderPrice(13986)
                ->setBuyOrderPrice(12235)
                ->setRaw(false),
        ];
    }

    private function getRawResources(): array
    {
        return [
            (new ResourceEntity())
                ->setTier(30)
                ->setName('planks')
                ->setCity('TestCity')
                ->setRealName('planks')
                ->setSellOrderPrice(1398)
                ->setBuyOrderPrice(1223)
                ->setRaw(true),
        ];
    }
}
