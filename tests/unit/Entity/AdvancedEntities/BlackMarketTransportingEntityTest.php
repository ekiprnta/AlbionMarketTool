<?php

namespace unit\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\AdvancedEntitites\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BlackMarketTransportingEntityTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketTransportEntity $bmtEntity;

    protected function setUp(): void
    {
        $this->bmtEntity = new BlackMarketTransportEntity(
            (new ItemEntity())
                ->setTier(71)
                ->setName('3h_axe')
                ->setCity('BlackMarket')
                ->setSellOrderPrice(441992)
                ->setBuyOrderPrice(168594)
                ->setWeaponGroup('axe')
                ->setRealName('greatAxe')
                ->setPrimaryResource('metalBar')
                ->setPrimaryResourceAmount(20)
                ->setSecondaryResource('planks')
                ->setSecondaryResourceAmount(12)
                ->refreshFame()
                ->refreshItemValue(),
        );
    }

    public function testAmount(): void
    {
        $this->bmtEntity->setAmount(5);
        $this->assertEquals(5, $this->bmtEntity->getAmount());
    }

    public function testProfit(): void
    {
        $this->bmtEntity->setProfit(5.47);
        $this->assertEquals(5.47, $this->bmtEntity->getProfit());
    }

    public function testSingleProfit(): void
    {
        $this->bmtEntity->setSingleProfit(12.47);
        $this->assertEquals(12.47, $this->bmtEntity->getSingleProfit());
    }

    public function testProfitGrade(): void
    {
        $this->bmtEntity->setProfitGrade('S');
        $this->assertEquals('S', $this->bmtEntity->getProfitGrade());
    }

    public function testTierColor(): void
    {
        $this->assertEquals('7', $this->bmtEntity->getTierColor());
    }

    public function testTotalCost(): void
    {
        $this->bmtEntity->setTotalCost(5);
        $this->assertEquals(5, $this->bmtEntity->getTotalCost());
    }

    public function testProfitPercentage(): void
    {
        $this->bmtEntity->setProfitPercentage(130.3);
        $this->assertEquals(130.3, $this->bmtEntity->getProfitPercentage());
    }
}
