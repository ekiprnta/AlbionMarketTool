<?php

declare(strict_types=1);

namespace unit\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\AdvancedEntitites\NoSpecEntity;
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
        $noSpecEntity->setMaterialCost(10000);
        $noSpecEntity->setProfit(420);
        $noSpecEntity->setProfitPercentage(12.5);
        $noSpecEntity->setProfitGrade('A');

        $this->assertEquals(41, $noSpecEntity->getSpecialItem()->getTier());
        $this->assertEquals(41, $noSpecEntity->getDefaultItem()->getTier());
        $this->assertEquals('material', $noSpecEntity->getSecondResource()->getName());
        $this->assertEquals('material', $noSpecEntity->getArtifact()->getName());
        $this->assertEquals(10000, $noSpecEntity->getMaterialCost());
        $this->assertEquals(420, $noSpecEntity->getProfit());
        $this->assertEquals(12.5, $noSpecEntity->getProfitPercentage());
        $this->assertEquals('A', $noSpecEntity->getProfitGrade());
        $this->assertEquals(4, $noSpecEntity->getTierColor());
    }
}
