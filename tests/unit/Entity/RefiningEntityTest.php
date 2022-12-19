<?php

namespace unit\Entity;

use MZierdt\Albion\Entity\RefiningEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class RefiningEntityTest extends TestCase
{
    use ProphecyTrait;

    private RefiningEntity $refiningEntity;

    private ResourceEntity $resourceEntity;

    protected function setUp(): void
    {
        $this->resourceEntity = new ResourceEntity([
            'tier' => '2',
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

        $this->refiningEntity = new RefiningEntity($this->resourceEntity);
    }

    public function testGetResourceEntity(): void
    {
        $this->assertEquals($this->resourceEntity, $this->refiningEntity->getResourceEntity());
    }

    public function testGetTierColor(): void
    {
        $this->assertEquals('2', $this->refiningEntity->getTierColor());
    }

    public function testGetRawResource(): void
    {
        $this->refiningEntity->setRawResource($this->resourceEntity);
        $this->assertEquals($this->resourceEntity, $this->refiningEntity->getRawResource());
    }

    public function testGetLowerResource(): void
    {
        $this->refiningEntity->setLowerResource($this->resourceEntity);
        $this->assertEquals($this->resourceEntity, $this->refiningEntity->getLowerResource());
    }

    public function testGetAmount(): void
    {
        $this->refiningEntity->setAmount(15);
        $this->assertEquals(15, $this->refiningEntity->getAmount());
    }

    public function testGetProfit(): void
    {
        $this->refiningEntity->setProfit(15.23);
        $this->assertEquals(15.23, $this->refiningEntity->getProfit());
    }

    public function testGetWeightAmountQuotient(): void
    {
        $this->refiningEntity->setWeightAmountQuotient(182.24);
        $this->assertEquals(182.24, $this->refiningEntity->getWeightAmountQuotient());
    }

    public function testGetProfitGrade(): void
    {
        $this->refiningEntity->setProfitGrade('A');
        $this->assertEquals('A', $this->refiningEntity->getProfitGrade());
    }

    public function testGetSingleProfit(): void
    {
        $this->refiningEntity->setSingleProfit(1.2);
        $this->assertEquals(1.2, $this->refiningEntity->getSingleProfit());
    }

    public function testGetAmountRawResource(): void
    {
        $this->refiningEntity->setAmountRawResource(4);
        $this->assertEquals(4, $this->refiningEntity->getAmountRawResource());
    }
}