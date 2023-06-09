<?php

declare(strict_types=1);

namespace unit\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\AdvancedEntities\NoSpecEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class NoSpecEntityTest extends TestCase
{
    use ProphecyTrait;

    public function testNoSpecEntity(): void
    {
        $cape = (new ItemEntity())->setTier(41);
        $materialEntity = (new MaterialEntity())->setName('material');

        $noSpecEntity = new NoSpecEntity($cape);
        $noSpecEntity->setDefaultItem($cape);
        $noSpecEntity->setSecondResource($materialEntity);
        $noSpecEntity->setArtifact($materialEntity);

        $this->assertEquals(41, $noSpecEntity->getSpecialItem()->getTier());
        $this->assertEquals(41, $noSpecEntity->getDefaultItem()->getTier());
        $this->assertEquals('material', $noSpecEntity->getSecondResource()->getName());
        $this->assertEquals('material', $noSpecEntity->getArtifact()->getName());
        $this->assertEquals(4, $noSpecEntity->getTierColor());
    }
}
