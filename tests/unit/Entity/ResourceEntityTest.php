<?php

namespace unit\Entity;

use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ResourceEntityTest extends TestCase
{
    use ProphecyTrait;

    public function testResourceEntity(): void
    {
        $resourceEntity = (new ResourceEntity())
            ->setTier(40)
            ->setName('Test')
            ->setCity('TestCity')
            ->setSellOrderPrice(100)
            ->setBuyOrderPrice(200)
            ->setRealName('wood')
            ->setBonusCity('bonusCity')
            ->setRaw(true);

        $this->assertEquals(40, $resourceEntity->getTier());
        $this->assertEquals('Test', $resourceEntity->getName());
        $this->assertEquals('TestCity', $resourceEntity->getCity());
        $this->assertEquals(100, $resourceEntity->getSellOrderPrice());
        $this->assertEquals(200, $resourceEntity->getBuyOrderPrice());
        $this->assertEquals('wood', $resourceEntity->getRealName());
        $this->assertEquals('bonusCity', $resourceEntity->getBonusCity());
        $this->assertEquals(true, $resourceEntity->isRaw());
    }
}
