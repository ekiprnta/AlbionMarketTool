<?php

namespace unit\Entity;

use MZierdt\Albion\Entity\ItemEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ItemEntityTest extends TestCase
{
    use ProphecyTrait;

    public function testItemEntity(): void
    {
        $itemEntity = (new ItemEntity())
            ->setTier(40)
            ->setName('Test')
            ->setCity('TestCity')
            ->setSellOrderPrice(100)
            ->setBuyOrderPrice(200)
            ->setClass('warrior')
            ->setRealName('pike')
            ->setWeaponGroup('spear')
            ->setQuality(2)
            ->setPrimaryResource('wood')
            ->setPrimaryResourceAmount(16)
            ->setSecondaryResource('secRes')
            ->setSecondaryResourceAmount(8)
            ->setBonusCity('bonusCity')
            ->refreshFame()
            ->refreshItemValue();

        $this->assertEquals(40, $itemEntity->getTier());
        $this->assertEquals('Test', $itemEntity->getName());
        $this->assertEquals('TestCity', $itemEntity->getCity());
        $this->assertEquals(100, $itemEntity->getSellOrderPrice());
        $this->assertEquals(200, $itemEntity->getBuyOrderPrice());
        $this->assertEquals('warrior', $itemEntity->getClass());
        $this->assertEquals('pike', $itemEntity->getRealName());
        $this->assertEquals('spear', $itemEntity->getWeaponGroup());
        $this->assertEquals(2, $itemEntity->getQuality());
        $this->assertEquals('wood', $itemEntity->getPrimaryResource());
        $this->assertEquals(16, $itemEntity->getPrimaryResourceAmount());
        $this->assertEquals('secRes', $itemEntity->getSecondaryResource());
        $this->assertEquals(8, $itemEntity->getSecondaryResourceAmount());
        $this->assertEquals('bonusCity', $itemEntity->getBonusCity());
        $this->assertEquals(540, $itemEntity->getFame());
        $this->assertEquals(384, $itemEntity->getItemValue());
        $this->assertEquals(24, $itemEntity->getTotalResourceAmount());
    }
}
