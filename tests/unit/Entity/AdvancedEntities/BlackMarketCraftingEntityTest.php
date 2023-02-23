<?php

namespace unit\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BlackMarketCraftingEntityTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketCraftingEntity $bmcEntity;

    protected function setUp(): void
    {
        $this->bmcEntity = new BlackMarketCraftingEntity(
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

    public function testJournalAmountPerItem(): void
    {
        $this->bmcEntity->setJournalAmountPerItem(0.002);
        $this->assertEquals(0.002, $this->bmcEntity->getJournalAmountPerItem());
    }


    public function testPrimResourceAmount(): void
    {
        $this->bmcEntity->setPrimResourceTotalAmount(16);
        $this->assertEquals(16, $this->bmcEntity->getPrimResourceTotalAmount());
    }

    public function testSecResourceAmount(): void
    {
        $this->bmcEntity->setSecResourceTotalAmount(20);
        $this->assertEquals(20, $this->bmcEntity->getSecResourceTotalAmount());
    }

    public function testJournalAmount(): void
    {
        $this->bmcEntity->setJournalTotalAmount(35);
        $this->assertEquals(35, $this->bmcEntity->getJournalTotalAmount());
    }

    public function testCraftingFee(): void
    {
        $this->bmcEntity->setCraftingFee(56900.04);
        $this->assertEquals(56900.04, $this->bmcEntity->getCraftingFee());
    }

    public function testProfitJournals(): void
    {
        $this->bmcEntity->setProfitJournals(12.3);
        $this->assertEquals(12.3, $this->bmcEntity->getProfitJournals());
    }

    public function testFameAmount(): void
    {
        $this->bmcEntity->setFameAmount(123.44);
        $this->assertEquals(123.44, $this->bmcEntity->getFameAmount());
    }

    public function testTierColor(): void
    {
        $this->assertEquals('7', $this->bmcEntity->getTierColor());
    }

    public function testItemValue(): void
    {
        $this->bmcEntity->setItemValue(123);
        $this->assertEquals(123, $this->bmcEntity->getItemValue());
    }
}
