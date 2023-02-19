<?php

namespace unit\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BlackMarketTransportingEntityTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketTransportEntity $bmtEntity;

    protected function setUp(): void
    {
        $this->bmtEntity = new BlackMarketTransportEntity($this->getTesEntity());
    }

    public function testGetBmItem(): void
    {
        $testItemEntity = $this->getTesEntity();
        $this->assertEquals($testItemEntity, $this->bmtEntity->getBmItem());
    }

    public function testGetCityItem(): void
    {
        $testItemEntity = $this->getTesEntity();
        $this->bmtEntity->setCityItem($testItemEntity);
        $this->assertEquals($testItemEntity, $this->bmtEntity->getCityItem());
    }

    private function getTesEntity(): ItemEntity
    {
        return (new ItemEntity())
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
            ->refreshItemValue();
    }
}
