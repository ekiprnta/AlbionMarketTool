<?php

namespace unit\Entity;

use MZierdt\Albion\Entity\JournalEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class JournalEntityTest extends TestCase
{
    use ProphecyTrait;

    public function testJournalEntity(): void
    {
        $journalEntity = (new JournalEntity())
            ->setTier(40)
            ->setName('Test')
            ->setCity('TestCity')
            ->setSellOrderPrice(100)
            ->setBuyOrderPrice(200)
            ->setClass('warrior')
            ->setFameToFill(150)
            ->setFillStatus('empty');

        $this->assertEquals(40, $journalEntity->getTier());
        $this->assertEquals('Test', $journalEntity->getName());
        $this->assertEquals('TestCity', $journalEntity->getCity());
        $this->assertEquals(100, $journalEntity->getSellOrderPrice());
        $this->assertEquals(200, $journalEntity->getBuyOrderPrice());
        $this->assertEquals('warrior', $journalEntity->getClass());
        $this->assertEquals(150, $journalEntity->getFameToFill());
        $this->assertEquals('empty', $journalEntity->getFillStatus());
    }
}
