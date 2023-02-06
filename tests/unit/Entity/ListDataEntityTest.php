<?php

declare(strict_types=1);

namespace unit\Entity;

use MZierdt\Albion\Entity\ListDataEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ListDataEntityTest extends TestCase
{
    use ProphecyTrait;

    private ResourceEntity $resource;
    private ListDataEntity $listDataEntity;

    public function testGetMartlockObject(): void
    {
        $this->listDataEntity->setMartlockObject($this->resource);
        $this->assertEquals($this->resource, $this->listDataEntity->getMartlockObject());
    }

    public function testGetBridgewatchObject(): void
    {
        $this->listDataEntity->setBridgewatchObject($this->resource);
        $this->assertEquals($this->resource, $this->listDataEntity->getBridgewatchObject());
    }

    public function testGetLymhurstObject(): void
    {
        $this->listDataEntity->setLymhurstObject($this->resource);
        $this->assertEquals($this->resource, $this->listDataEntity->getLymhurstObject());
    }

    public function testGetThetfordObject(): void
    {
        $this->listDataEntity->setThetfordObject($this->resource);
        $this->assertEquals($this->resource, $this->listDataEntity->getThetfordObject());
    }

    public function testGetFortsterlingObject(): void
    {
        $this->assertEquals($this->resource, $this->listDataEntity->getFortsterlingObject());
    }

    public function testGetCheapestObjectCitySellOrder(): void
    {
        $this->listDataEntity->setCheapestObjectCitySellOrder('abc');
        $this->assertEquals('abc', $this->listDataEntity->getCheapestObjectCitySellOrder());
    }

    public function testGetCheapestObjectCityBuyOrder(): void
    {
        $this->listDataEntity->setCheapestObjectCityBuyOrder('abc');
        $this->assertEquals('abc', $this->listDataEntity->getCheapestObjectCityBuyOrder());
    }

    public function testGetMostExpensiveObjectCitySellOrder(): void
    {
        $this->listDataEntity->setMostExpensiveObjectCitySellOrder('abc');
        $this->assertEquals('abc', $this->listDataEntity->getMostExpensiveObjectCitySellOrder());
    }

    public function testGetMostExpensiveObjectCityBuyOrder(): void
    {
        $this->listDataEntity->setMostExpensiveObjectCityBuyOrder('abc');
        $this->assertEquals('abc', $this->listDataEntity->getMostExpensiveObjectCityBuyOrder());
    }

    public function testGetTierColor(): void
    {
        $this->assertEquals('2', $this->listDataEntity->getTierColor());
    }

    protected function setUp(): void
    {
        $this->resource = new ResourceEntity([
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
        ]);
        $this->listDataEntity = new ListDataEntity($this->resource);
    }
}
