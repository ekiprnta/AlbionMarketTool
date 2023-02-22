<?php

namespace integration\AlbionMarket;

use MZierdt\Albion\AlbionMarket\TransmutationHelper;
use MZierdt\Albion\AlbionMarket\TransmutationService;
use MZierdt\Albion\Entity\AdvancedEntities\TransmutationEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\GlobalDiscountService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class TransmutationServiceITest extends TestCase
{
    use ProphecyTrait;

    public function testGetTransmutationByCityException(): void
    {
        /** @var ResourceRepository|ObjectProphecy $resourceRepository */
        $resourceRepository = $this->prophesize(ResourceRepository::class);
        $transmutationHelper = new TransmutationHelper();
        /** @var ConfigService|ObjectProphecy $configService */
        $configService = $this->prophesize(ConfigService::class);
        /** @var GlobalDiscountService|ObjectProphecy $discountService */
        $discountService = $this->prophesize(GlobalDiscountService::class);

        $transmutationService = new TransmutationService(
            $resourceRepository->reveal(),
            $transmutationHelper,
            $configService->reveal(),
            $discountService->reveal(),
        );

        $resourceRepository->getRawResourcesByCity('Sterling')
            ->willReturn($this->getRawResources());
        $configService->getTransmutationWays()
            ->willReturn([
                '40to60' => ['50', '60'],
            ]);
        $configService->getTransmutationCost()
            ->willReturn(['50' => ['tier' => 1000], '60' => ['tier' => 2000]]);
        $discountService->getGlobalDiscount()
            ->willReturn(0.01);

        $testData = $transmutationService->getTransmutationByCity('Sterling');

        /** @var TransmutationEntity $transEntity */
        foreach ($testData as $transEntity) {
            $this->assertEquals(-4011.965, $transEntity->getProfit());
            $this->assertEquals('D', $transEntity->getProfitGrade());
            $this->assertEquals('4', $transEntity->getStartTierColor());
            $this->assertEquals('6', $transEntity->getEndTierColor());
            $this->assertEquals(2970.0, $transEntity->getTransmutePrice());
            $this->assertEquals('40to60', $transEntity->getPathName());
            $this->assertEquals([50, 60], $transEntity->getTransmutationPath());
        }
    }

    private function getRawResources(): array
    {
        $resourceA = (new ResourceEntity())
            ->setTier(40)
            ->setName('fiber')
            ->setCity('Sterling')
            ->setRealName('fiber')
            ->setSellOrderPrice(61)
            ->setBuyOrderPrice(58)
            ->setRaw(true);
        $resourceB = (new ResourceEntity())
            ->setTier(60)
            ->setName('fiber')
            ->setCity('Sterling')
            ->setRealName('fiber')
            ->setSellOrderPrice(1099)
            ->setBuyOrderPrice(1098)
            ->setRaw(true);
        $resourceC = (new ResourceEntity())
            ->setTier(40)
            ->setName('ore')
            ->setCity('Sterling')
            ->setRealName('ore')
            ->setSellOrderPrice(61)
            ->setBuyOrderPrice(58)
            ->setRaw(true);
        $resourceD = (new ResourceEntity())
            ->setTier(60)
            ->setName('ore')
            ->setCity('Sterling')
            ->setRealName('ore')
            ->setSellOrderPrice(1099)
            ->setBuyOrderPrice(1098)
            ->setRaw(true);
        $resourceE = (new ResourceEntity())
            ->setTier(40)
            ->setName('hide')
            ->setCity('Sterling')
            ->setRealName('hide')
            ->setSellOrderPrice(61)
            ->setBuyOrderPrice(58)
            ->setRaw(true);
        $resourceF = (new ResourceEntity())
            ->setTier(60)
            ->setName('hide')
            ->setCity('Sterling')
            ->setRealName('hide')
            ->setSellOrderPrice(1099)
            ->setBuyOrderPrice(1098)
            ->setRaw(true);
        $resourceG = (new ResourceEntity())
            ->setTier(40)
            ->setName('wood')
            ->setCity('Sterling')
            ->setRealName('wood')
            ->setSellOrderPrice(61)
            ->setBuyOrderPrice(58)
            ->setRaw(true);
        $resourceH = (new ResourceEntity())
            ->setTier(60)
            ->setName('wood')
            ->setCity('Sterling')
            ->setRealName('wood')
            ->setSellOrderPrice(1099)
            ->setBuyOrderPrice(1098)
            ->setRaw(true);

        return [$resourceA, $resourceB, $resourceC, $resourceD, $resourceE, $resourceF, $resourceG, $resourceH];
    }
}
