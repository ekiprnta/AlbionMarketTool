<?php

namespace unit\Entity;

use MZierdt\Albion\Entity\BlackMarketCraftingEntity;
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

    public function testTotalAmount(): void
    {
        $this->bmcEntity->setTotalAmount(128);
        $this->assertEquals(128, $this->bmcEntity->getTotalAmount());
    }

    public function testPrimResourceAmount(): void
    {
        $this->bmcEntity->setPrimResourceAmount(16);
        $this->assertEquals(16, $this->bmcEntity->getPrimResourceAmount());
    }

    public function testSecResourceAmount(): void
    {
        $this->bmcEntity->setSecResourceAmount(20);
        $this->assertEquals(20, $this->bmcEntity->getSecResourceAmount());
    }

    public function testJournalAmount(): void
    {
        $this->bmcEntity->setJournalAmount(35);
        $this->assertEquals(35, $this->bmcEntity->getJournalAmount());
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

    public function testProfit(): void
    {
        $this->bmcEntity->setProfit(210023.3);
        $this->assertEquals(210023.3, $this->bmcEntity->getProfit());
    }

    public function testWeightProfitQuotient(): void
    {
        $this->bmcEntity->setProfitQuotient(1400.12);
        $this->assertEquals(1400.12, $this->bmcEntity->getProfitQuotient());
    }

    public function testColorGrade(): void
    {
        $this->bmcEntity->setColorGrade('D');
        $this->assertEquals('D', $this->bmcEntity->getColorGrade());
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
