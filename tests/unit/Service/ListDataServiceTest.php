<?php

declare(strict_types=1);

namespace unit\Service;

use MZierdt\Albion\Entity\ListDataEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ListDataHelper;
use MZierdt\Albion\Service\ListDataService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ListDataServiceTest extends TestCase
{
    use ProphecyTrait;

    private ListDataService $listDataService;
    private ResourceRepository|ObjectProphecy $resourceRepository;

    public function testGetAllRawResources(): void
    {
        $resources = [
            new ResourceEntity([
                'tier' => 20,
                'name' => 'cloth',
                'city' => 'Fort Sterling',
                'realName' => 'cloth',
                'sellOrderPrice' => '27',
                'sellOrderPriceDate' => '2022-12-08 10:45:00',
                'buyOrderPrice' => '26',
                'buyOrderPriceDate' => '2022-12-08 10:45:00',
                'bonusCity' => 'Lymhurst',
                'amountInStorage' => null,
            ]),
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

        $rawResources = $this->listDataService->getAllRawResources();
        /** @var ListDataEntity $ldEntity */
        foreach ($rawResources as $ldEntity) {
            $this->assertEquals('Fort Sterling', $ldEntity->getCheapestObjectCitySellOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getCheapestObjectCityBuyOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getMostExpensiveObjectCityBuyOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getMostExpensiveObjectCitySellOrder());
            $this->assertEquals('2', $ldEntity->getTierColor());
        }
    }

    public function testGetAllResources(): void
    {
        $resources = [
            new ResourceEntity([
                'tier' => '20',
                'name' => 'cloth',
                'city' => 'Fort Sterling',
                'realName' => 'cloth',
                'sellOrderPrice' => '27',
                'sellOrderPriceDate' => '2022-12-08 10:45:00',
                'buyOrderPrice' => '26',
                'buyOrderPriceDate' => '2022-12-08 10:45:00',
                'bonusCity' => 'Lymhurst',
                'amountInStorage' => null,
            ]),
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

        $rawResources = $this->listDataService->getAllResources();
        /** @var ListDataEntity $ldEntity */
        foreach ($rawResources as $ldEntity) {
            $this->assertEquals('Fort Sterling', $ldEntity->getCheapestObjectCitySellOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getCheapestObjectCityBuyOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getMostExpensiveObjectCityBuyOrder());
            $this->assertEquals('Fort Sterling', $ldEntity->getMostExpensiveObjectCitySellOrder());
            $this->assertEquals('2', $ldEntity->getTierColor());
        }
    }

    protected function setUp(): void
    {
        $this->resourceRepository = $this->prophesize(ResourceRepository::class);
        $this->listDataService = new ListDataService($this->resourceRepository->reveal(), new ListDataHelper(),);
    }
}
