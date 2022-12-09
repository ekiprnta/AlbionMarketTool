<?php

namespace unit\Entity;

use MZierdt\Albion\Entity\JournalEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class JournalEntityTest extends TestCase
{
    use ProphecyTrait;

    public function testGettersA(): void
    {
        $entity = $this->getJournalEntityA();
        $this->assertEquals('2', $entity->getTier());
        $this->assertEquals('journal_hunter_empty', $entity->getName());
        $this->assertEquals('Fort Sterling', $entity->getCity());
        $this->assertEquals(237, $entity->getSellOrderPrice());
//        $this->assertEquals(25765, $entity->getSellOrderAge());
        $this->assertEquals(6, $entity->getBuyOrderPrice());
//        $this->assertEquals(25765, $entity->getBuyOrderAge());
        $this->assertEquals('', $entity->getRealName());
        $this->assertEquals(0.2, $entity->getWeight());
        $this->assertEquals('hunter', $entity->getClass());
        $this->assertEquals(900, $entity->getFameToFill());
        $this->assertEquals('empty', $entity->getFillStatus());
    }

    public function testGettersB(): void
    {
        $entity = $this->getJournalEntityB();
        $this->assertEquals('41', $entity->getTier());
        $this->assertEquals('journal_mage_full', $entity->getName());
        $this->assertEquals('Fort Sterling', $entity->getCity());
        $this->assertEquals(237, $entity->getSellOrderPrice());
//        $this->assertEquals(25765, $entity->getSellOrderAge());
        $this->assertEquals(6, $entity->getBuyOrderPrice());
//        $this->assertEquals(25765, $entity->getBuyOrderAge());
        $this->assertEquals('', $entity->getRealName());
        $this->assertEquals(0.2, $entity->getWeight());
        $this->assertEquals('mage', $entity->getClass());
        $this->assertEquals(900, $entity->getFameToFill());
        $this->assertEquals('full', $entity->getFillStatus());
    }

    public function testGettersC(): void
    {
        $entity = $this->getJournalEntityC();
        $this->assertEquals('73', $entity->getTier());
        $this->assertEquals('name', $entity->getName());
        $this->assertEquals('city', $entity->getCity());
        $this->assertEquals(237, $entity->getSellOrderPrice());
//        $this->assertEquals(25765, $entity->getSellOrderAge());
        $this->assertEquals(6, $entity->getBuyOrderPrice());
//        $this->assertEquals(25765, $entity->getBuyOrderAge());
        $this->assertEquals('', $entity->getRealName());
        $this->assertEquals(0.2, $entity->getWeight());
        $this->assertEquals('warrior', $entity->getClass());
        $this->assertEquals(2345465, $entity->getFameToFill());
        $this->assertEquals('full', $entity->getFillStatus());
    }

    public function getJournalEntityA(): JournalEntity
    {
        return new JournalEntity([
            'tier' => '2',
            'name' => 'journal_hunter_empty',
            'city' => 'Fort Sterling',
            'fameToFill' => '900',
            'sellOrderPrice' => '237',
            'sellOrderPriceDate' => null,
            'buyOrderPrice' => '6',
            'buyOrderPriceDate' => null,
            'weight' => '0.2',
            'fillStatus' => 'empty',
            'class' => 'hunter',
        ]);
    }

    public function getJournalEntityB(): JournalEntity
    {
        return new JournalEntity([
            'tier' => '41',
            'name' => 'journal_mage_full',
            'city' => 'Fort Sterling',
            'fameToFill' => '900',
            'sellOrderPrice' => '237',
            'sellOrderPriceDate' => '2022-12-08 19:55:00',
            'buyOrderPrice' => '6',
            'buyOrderPriceDate' => '2022-12-01 21:20:00',
            'weight' => '0.2',
            'fillStatus' => 'full',
            'class' => 'mage',
        ]);
    }

    public function getJournalEntityC(): JournalEntity
    {
        return new JournalEntity([
            'tier' => '73',
            'name' => 'name',
            'city' => 'city',
            'fameToFill' => '2345465',
            'sellOrderPrice' => '237',
            'sellOrderPriceDate' => '2002-12-08 19:55:00',
            'buyOrderPrice' => '6',
            'buyOrderPriceDate' => '2013-12-01 21:20:00',
            'weight' => '0.2',
            'fillStatus' => 'full',
            'class' => 'warrior',
        ]);
    }
}