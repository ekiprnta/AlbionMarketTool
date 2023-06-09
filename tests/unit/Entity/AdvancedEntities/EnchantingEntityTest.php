<?php

declare(strict_types=1);

namespace unit\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\AdvancedEntities\EnchantingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class EnchantingEntityTest extends TestCase
{
    use ProphecyTrait;

    private EnchantingEntity $enchantingEntity;
    private ItemEntity $itemEntity;

    public function testItemEntity(): void
    {
        $this->assertEquals($this->itemEntity, $this->enchantingEntity->getBaseItem());
    }

    public function testHigherEnchantmentItem(): void
    {
        $this->enchantingEntity->setHigherEnchantmentItem($this->itemEntity);
        $this->assertEquals($this->itemEntity, $this->enchantingEntity->getHigherEnchantmentItem());
    }

    public function testEnchantmentMaterial(): void
    {
        $materialEntity = (new MaterialEntity())
            ->setTier(40)
            ->setName('relic')
            ->setCity('Martlock')
            ->setSellOrderPrice(601)
//            ->setSellOrderDate(284)
            ->setBuyOrderPrice(600)
//            ->setBuyOrderDate(234)
            ->setRealName('relic');

        $this->enchantingEntity->setEnchantmentMaterial($materialEntity);
        $this->assertEquals($materialEntity, $this->enchantingEntity->getEnchantmentMaterial());
    }

    public function testBaseEnchantment(): void
    {
        $this->enchantingEntity->setBaseEnchantment(2);
        $this->assertEquals(2, $this->enchantingEntity->getBaseEnchantment());
    }

    public function testMaterialAmount(): void
    {
        $this->enchantingEntity->setMaterialAmount(192);
        $this->assertEquals(192, $this->enchantingEntity->getMaterialAmount());
    }

    public function testTierColor(): void
    {
        $this->assertEquals(4, $this->enchantingEntity->getTierColor());
    }

    protected function setUp(): void
    {
        $this->itemEntity = (new ItemEntity())
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
        $this->enchantingEntity = new EnchantingEntity($this->itemEntity);
    }
}
