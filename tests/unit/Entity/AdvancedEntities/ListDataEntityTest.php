<?php

declare(strict_types=1);

namespace unit\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\AdvancedEntities\ListDataEntity;
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
        $this->assertEquals('7', $this->listDataEntity->getTierColor());
    }

    protected function setUp(): void
    {
        $this->resource = (new ResourceEntity())
            ->setTier(71)
            ->setName('metalBar')
            ->setCity('TestCity')
            ->setRealName('metalBar')
            ->setSellOrderPrice(13986)
            ->setBuyOrderPrice(12235);
        $this->listDataEntity = new ListDataEntity($this->resource);
    }
}
