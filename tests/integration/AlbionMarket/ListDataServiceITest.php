<?php

declare(strict_types=1);

namespace integration\AlbionMarket;

use MZierdt\Albion\AlbionMarket\ListDataService;
use MZierdt\Albion\Entity\AdvancedEntities\ListDataEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ListDataServiceITest extends TestCase
{
    use ProphecyTrait;

    public function testRefining(): void
    {
        $listDataService = new ListDataService();

        $resourceEntity = (new ResourceEntity())
            ->setTier(61)
            ->setName('leather')
            ->setCity('TestCity')
            ->setSellOrderPrice(7910)
            ->setBuyOrderPrice(7878);

        $baseListDataEntity = new ListDataEntity($resourceEntity);

        $listDataEntity = $listDataService->calculateListDataEntity(
            $baseListDataEntity,
            $this->getLymhurstResources(),
            $this->getBridgewatchResources(),
            $this->getMartlockResources(),
            $this->getThetfordResources(),
            'TestCity'
        );

        $this->assertEquals('Lymhurst', $listDataEntity->getMostExpensiveObjectCitySellOrder());
        $this->assertEquals('Martlock', $listDataEntity->getCheapestObjectCitySellOrder());

        $this->assertEquals('Bridgewatch', $listDataEntity->getCheapestObjectCityBuyOrder());
        $this->assertEquals('Thetford', $listDataEntity->getMostExpensiveObjectCityBuyOrder());
    }

    private function getLymhurstResources(): array
    {
        $refinedA = (new ResourceEntity())
            ->setTier(61)
            ->setName('leather')
            ->setCity('Lymhurst')
            ->setSellOrderPrice(8000)
            ->setBuyOrderPrice(7878);
        return [$refinedA];
    }

    private function getBridgewatchResources(): array
    {
        $refinedA = (new ResourceEntity())
            ->setTier(61)
            ->setName('leather')
            ->setCity('Bridgewatch')
            ->setSellOrderPrice(7910)
            ->setBuyOrderPrice(7000);
        return [$refinedA];
    }

    private function getMartlockResources(): array
    {
        $refinedA = (new ResourceEntity())
            ->setTier(61)
            ->setName('leather')
            ->setCity('Martlock')
            ->setSellOrderPrice(7000)
            ->setBuyOrderPrice(7878);
        return [$refinedA];
    }

    private function getThetfordResources(): array
    {
        $refinedA = (new ResourceEntity())
            ->setTier(61)
            ->setName('leather')
            ->setCity('Thetford')
            ->setSellOrderPrice(7910)
            ->setBuyOrderPrice(8000);
        return [$refinedA];
    }
}