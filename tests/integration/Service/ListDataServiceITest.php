<?php

declare(strict_types=1);

namespace integration\Service;

use MZierdt\Albion\Entity\ListDataEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ListDataHelper;
use MZierdt\Albion\Service\ListDataService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ListDataServiceITest extends TestCase
{
    use ProphecyTrait;

    private ListDataService $listDataService;
    private ResourceRepository|ObjectProphecy $resourceRepository;

    public function testGetAllRawResources(): void
    {
        $resources = [
            (new ResourceEntity())
                ->setTier(71)
                ->setName('metalBar')
                ->setCity('TestCity')
                ->setRealName('metalBar')
                ->setSellOrderPrice(13986)
                ->setBuyOrderPrice(12235),
        ];

        $this->resourceRepository->getRawResourcesByCity('Fort Sterling')
            ->willReturn($resources);
        $this->resourceRepository->getRawResourcesByCity('Lymhurst')
            ->willReturn($resources);
        $this->resourceRepository->getRawResourcesByCity('Bridgewatch')
            ->willReturn($resources);
        $this->resourceRepository->getRawResourcesByCity('Martlock')
            ->willReturn($resources);
        $this->resourceRepository->getRawResourcesByCity('Thetford')
            ->willReturn($resources);

        $rawResources = $this->listDataService->getResources('rawResource');
        /** @var ListDataEntity $ldEntity */
        foreach ($rawResources as $ldEntity) {
            $this->assertEquals('Fort Sterling', $ldEntity->getCheapestObjectCitySellOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getCheapestObjectCityBuyOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getMostExpensiveObjectCityBuyOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getMostExpensiveObjectCitySellOrder());
            $this->assertEquals('7', $ldEntity->getTierColor());
        }
    }

    public function testGetAllResources(): void
    {
        $resources = [
            (new ResourceEntity())
                ->setTier(71)
                ->setName('metalBar')
                ->setCity('TestCity')
                ->setRealName('metalBar')
                ->setSellOrderPrice(13986)
                ->setBuyOrderPrice(12235),
        ];

        $this->resourceRepository->getResourcesByCity('Fort Sterling')
            ->willReturn($resources);
        $this->resourceRepository->getResourcesByCity('Lymhurst')
            ->willReturn($resources);
        $this->resourceRepository->getResourcesByCity('Bridgewatch')
            ->willReturn($resources);
        $this->resourceRepository->getResourcesByCity('Martlock')
            ->willReturn($resources);
        $this->resourceRepository->getResourcesByCity('Thetford')
            ->willReturn($resources);

        $rawResources = $this->listDataService->getResources('resource');
        /** @var ListDataEntity $ldEntity */
        foreach ($rawResources as $ldEntity) {
            $this->assertEquals('Fort Sterling', $ldEntity->getCheapestObjectCitySellOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getCheapestObjectCityBuyOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getMostExpensiveObjectCityBuyOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getMostExpensiveObjectCitySellOrder());
            $this->assertEquals('7', $ldEntity->getTierColor());
        }
    }

    protected function setUp(): void
    {
        $this->resourceRepository = $this->prophesize(ResourceRepository::class);
        $this->listDataService = new ListDataService($this->resourceRepository->reveal(), new ListDataHelper(),);
    }
}
