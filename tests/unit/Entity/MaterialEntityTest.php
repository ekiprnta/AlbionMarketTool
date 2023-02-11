<?php

declare(strict_types=1);

namespace unit\Entity;

use MZierdt\Albion\Entity\MaterialEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class MaterialEntityTest extends TestCase
{
    use ProphecyTrait;

    private MaterialEntity $materialEntity;

    public function testMaterialEntity(): void
    {
        $this->materialEntity->setTier(40);
        $this->materialEntity->setName('relic');
        $this->materialEntity->setCity('Martlock');
        $this->materialEntity->setSellOrderPrice(601);
        $this->materialEntity->setSellOrderAge(284);
        $this->materialEntity->setBuyOrderPrice(600);
        $this->materialEntity->setBuyOrderAge(234);
        $this->materialEntity->setRealName('relic');

        $this->assertEquals(40, $this->materialEntity->getTier());
        $this->assertEquals('relic', $this->materialEntity->getName());
        $this->assertEquals('Martlock', $this->materialEntity->getCity());
        $this->assertEquals(601, $this->materialEntity->getSellOrderPrice());
        $this->assertEquals(284, $this->materialEntity->getSellOrderAge());
        $this->assertEquals(600, $this->materialEntity->getBuyOrderPrice());
        $this->assertEquals(234, $this->materialEntity->getBuyOrderAge());
        $this->assertEquals('relic', $this->materialEntity->getRealName());
    }

    protected function setUp(): void
    {
        $this->materialEntity = new MaterialEntity();
    }
}
