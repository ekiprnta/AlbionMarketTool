<?php

namespace unit\Entity;

use MZierdt\Albion\Entity\ItemEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ItemEntityTest extends TestCase
{
    use ProphecyTrait;

    private ItemEntity $entityA;
    private ItemEntity $entityB;
    private ItemEntity $entityC;

    protected function setUp(): void
    {
        $this->entityA = $this->getItemEntityA();
        $this->entityB = $this->getItemEntityB();
        $this->entityC = $this->getItemEntityC();
    }

    public function testGettersA(): void
    {
        $this->assertEquals('20', $this->entityA->getTier());
        $this->assertEquals('2h_divinestaff', $this->entityA->getName());
        $this->assertEquals('Black Market', $this->entityA->getCity());
        $this->assertEquals(0, $this->entityA->getSellOrderPrice());
//        $this->assertEquals(25765, $this->entityA->getSellOrderAge());
        $this->assertEquals(0, $this->entityA->getBuyOrderPrice());
//        $this->assertEquals(25765, $this->entityA->getBuyOrderAge());
        $this->assertEquals('divineStaff', $this->entityA->getRealName());
        $this->assertEquals(3.2, $this->entityA->getWeight());
        $this->assertEquals('mage', $this->entityA->getClass());
        $this->assertEquals(48, $this->entityA->getFame());
        $this->assertEquals(128, $this->entityA->getItemValue());
        $this->assertEquals('holyStaff', $this->entityA->getWeaponGroup());
        $this->assertEquals(null, $this->entityA->getAmountInStorage());
        $this->assertEquals('2', $this->entityA->getQuality());
        $this->assertEquals('planks', $this->entityA->getPrimaryResource());
        $this->assertEquals(20, $this->entityA->getPrimaryResourceAmount());
        $this->assertEquals('cloth', $this->entityA->getSecondaryResource());
        $this->assertEquals(12, $this->entityA->getSecondaryResourceAmount());
        $this->assertEquals('Fort Sterling', $this->entityA->getBonusCity());
    }

    public function testGettersB(): void
    {
        $this->assertEquals('41', $this->entityB->getTier());
        $this->assertEquals('ABC', $this->entityB->getName());
        $this->assertEquals('city', $this->entityB->getCity());
        $this->assertEquals(5, $this->entityB->getSellOrderPrice());
//        $this->assertEquals(25765, $this->entityB->getSellOrderAge());
        $this->assertEquals(10, $this->entityB->getBuyOrderPrice());
//        $this->assertEquals(25765, $this->entityB->getBuyOrderAge());
        $this->assertEquals('test', $this->entityB->getRealName());
        $this->assertEquals(6.72, $this->entityB->getWeight());
        $this->assertEquals('mage', $this->entityB->getClass());
        $this->assertEquals(1440.0, $this->entityB->getFame());
        $this->assertEquals(1024, $this->entityB->getItemValue());
        $this->assertEquals('123', $this->entityB->getWeaponGroup());
        $this->assertEquals(null, $this->entityB->getAmountInStorage());
        $this->assertEquals('2', $this->entityB->getQuality());
        $this->assertEquals('planks', $this->entityB->getPrimaryResource());
        $this->assertEquals(20, $this->entityB->getPrimaryResourceAmount());
        $this->assertEquals('cloth', $this->entityB->getSecondaryResource());
        $this->assertEquals(12, $this->entityB->getSecondaryResourceAmount());
        $this->assertEquals('Fort Sterling', $this->entityB->getBonusCity());
    }

    public function testGettersC(): void
    {
        $this->assertEquals('73', $this->entityC->getTier());
        $this->assertEquals('name', $this->entityC->getName());
        $this->assertEquals('city', $this->entityC->getCity());
        $this->assertEquals(5, $this->entityC->getSellOrderPrice());
//        $this->assertEquals(25765, $this->entityC->getSellOrderAge());
        $this->assertEquals(10, $this->entityC->getBuyOrderPrice());
//        $this->assertEquals(25765, $this->entityC->getBuyOrderAge());
        $this->assertEquals('realName', $this->entityC->getRealName());
        $this->assertEquals(17.8125, $this->entityC->getWeight());
        $this->assertEquals('class', $this->entityC->getClass());
        $this->assertEquals(129000.0, $this->entityC->getFame());
        $this->assertEquals(25600, $this->entityC->getItemValue());
        $this->assertEquals('weaponGroup', $this->entityC->getWeaponGroup());
        $this->assertEquals(null, $this->entityC->getAmountInStorage());
        $this->assertEquals('0', $this->entityC->getQuality());
        $this->assertEquals('primResource', $this->entityC->getPrimaryResource());
        $this->assertEquals(12, $this->entityC->getPrimaryResourceAmount());
        $this->assertEquals('secResource', $this->entityC->getSecondaryResource());
        $this->assertEquals(13, $this->entityC->getSecondaryResourceAmount());
        $this->assertEquals('Fort Sterling', $this->entityC->getBonusCity());
    }

    private function getItemEntityA(): ItemEntity
    {
        return new ItemEntity([
            'tier' => '20',
            'name' => '2h_divinestaff',
            'weaponGroup' => 'holyStaff',
            'realName' => 'divineStaff',
            'class' => 'mage',
            'city' => 'Black Market',
            'quality' => '2',
            'sellOrderPrice' => null,
            'sellOrderPriceDate' => null,
            'buyOrderPrice' => null,
            'buyOrderPriceDate' => null,
            'primaryResource' => 'planks',
            'primaryResourceAmount' => '20',
            'secondaryResource' => 'cloth',
            'secondaryResourceAmount' => '12',
            'bonusCity' => 'Fort Sterling',
            'fameFactor' => null,
            'amountInStorage' => null,
        ]);
    }

    private function getItemEntityB(): ItemEntity
    {
        return new ItemEntity([
            'tier' => '41',
            'name' => 'ABC',
            'weaponGroup' => '123',
            'realName' => 'test',
            'class' => 'mage',
            'city' => 'city',
            'quality' => '2',
            'sellOrderPrice' => 5,
            'sellOrderPriceDate' => '2022-12-09 09:36:15',
            'buyOrderPrice' => 10,
            'buyOrderPriceDate' => '2022-12-09 09:36:15',
            'primaryResource' => 'planks',
            'primaryResourceAmount' => '20',
            'secondaryResource' => 'cloth',
            'secondaryResourceAmount' => '12',
            'bonusCity' => 'Fort Sterling',
            'fameFactor' => null,
            'amountInStorage' => null,
        ]);
    }

    private function getItemEntityC(): ItemEntity
    {
        return new ItemEntity([
            'tier' => '73',
            'name' => 'name',
            'weaponGroup' => 'weaponGroup',
            'realName' => 'realName',
            'class' => 'class',
            'city' => 'city',
            'quality' => 'quality',
            'sellOrderPrice' => 5,
            'sellOrderPriceDate' => '2000-01-01 12:12:12',
            'buyOrderPrice' => 10,
            'buyOrderPriceDate' => '2022-03-16 07:32:69',
            'primaryResource' => 'primResource',
            'primaryResourceAmount' => '12',
            'secondaryResource' => 'secResource',
            'secondaryResourceAmount' => '13',
            'bonusCity' => 'Fort Sterling',
            'fameFactor' => null,
            'amountInStorage' => null,
        ]);
    }
}
