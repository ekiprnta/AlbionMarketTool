<?php

namespace unit\Entity;

use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ResourceEntityTest extends TestCase
{
    use ProphecyTrait;

    public function testGettersA(): void
    {
        $entity = $this->getResourceEntityA();
        $this->assertEquals('2', $entity->getTier());
        $this->assertEquals('cloth', $entity->getName());
        $this->assertEquals('Fort Sterling', $entity->getCity());
        $this->assertEquals(27, $entity->getSellOrderPrice());
//        $this->assertEquals(25765, $entity->getSellOrderAge());
        $this->assertEquals(26, $entity->getBuyOrderPrice());
//        $this->assertEquals(25765, $entity->getBuyOrderAge());
        $this->assertEquals('cloth', $entity->getRealName());
        $this->assertEquals(0.23, $entity->getWeight());
        $this->assertEquals('', $entity->getClass());
        $this->assertEquals('Lymhurst', $entity->getBonusCity());
        $this->assertEquals(0, $entity->getAmountInStorage());
    }

    public function testGettersB(): void
    {
        $entity = $this->getResourceEntityB();
        $this->assertEquals('41', $entity->getTier());
        $this->assertEquals('leather', $entity->getName());
        $this->assertEquals('Bridgewatch', $entity->getCity());
        $this->assertEquals(376, $entity->getSellOrderPrice());
//        $this->assertEquals(25765, $entity->getSellOrderAge());
        $this->assertEquals(87, $entity->getBuyOrderPrice());
//        $this->assertEquals(25765, $entity->getBuyOrderAge());
        $this->assertEquals('cloth', $entity->getRealName());
        $this->assertEquals(0.51, $entity->getWeight());
        $this->assertEquals('', $entity->getClass());
        $this->assertEquals('Lymhurst', $entity->getBonusCity());
        $this->assertEquals(0, $entity->getAmountInStorage());
    }

    public function testGettersC(): void
    {
        $entity = $this->getResourceEntityC();
        $this->assertEquals('73', $entity->getTier());
        $this->assertEquals('metalBar', $entity->getName());
        $this->assertEquals('city', $entity->getCity());
        $this->assertEquals(4567, $entity->getSellOrderPrice());
//        $this->assertEquals(25765, $entity->getSellOrderAge());
        $this->assertEquals(3476543, $entity->getBuyOrderPrice());
//        $this->assertEquals(25765, $entity->getBuyOrderAge());
        $this->assertEquals('realName', $entity->getRealName());
        $this->assertEquals(1.71, $entity->getWeight());
        $this->assertEquals('', $entity->getClass());
        $this->assertEquals('bonusCity', $entity->getBonusCity());
        $this->assertEquals(23456, $entity->getAmountInStorage());
    }


    public function getResourceEntityA(): ResourceEntity
    {
        return new ResourceEntity([
            "tier" => "2",
            "name" => "cloth",
            "city" => "Fort Sterling",
            "realName" => "cloth",
            "sellOrderPrice" => "27",
            "sellOrderPriceDate" => "2022-12-08 10:45:00",
            "buyOrderPrice" => "26",
            "buyOrderPriceDate" => "2022-12-08 10:45:00",
            "bonusCity" => "Lymhurst",
            "amountInStorage" => null,
        ]);
    }

    public function getResourceEntityB(): ResourceEntity
    {
        return new ResourceEntity([
            "tier" => "41",
            "name" => "leather",
            "city" => "Bridgewatch",
            "realName" => "cloth",
            "sellOrderPrice" => "376",
            "sellOrderPriceDate" => "2022-02-08 10:45:00",
            "buyOrderPrice" => "87",
            "buyOrderPriceDate" => "2022-01-08 10:45:00",
            "bonusCity" => "Lymhurst",
            "amountInStorage" => null,
        ]);
    }

    public function getResourceEntityC(): ResourceEntity
    {
        return new ResourceEntity([
            "tier" => "73",
            "name" => "metalBar",
            "city" => "city",
            "realName" => "realName",
            "sellOrderPrice" => "4567",
            "sellOrderPriceDate" => null,
            "buyOrderPrice" => "3476543",
            "buyOrderPriceDate" => null,
            "bonusCity" => "bonusCity",
            "amountInStorage" => 23456,
        ]);
    }
}