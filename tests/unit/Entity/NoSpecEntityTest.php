<?php

declare(strict_types=1);

namespace unit\Entity;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use MZierdt\Albion\Entity\NoSpecEntity;
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
        $noSpecEntity->setDefaultCape($cape);
        $noSpecEntity->setSecondResource($materialEntity);
        $noSpecEntity->setArtifact($materialEntity);
        $noSpecEntity->setMaterialCost(10000);
        $noSpecEntity->setProfit(420);
        $noSpecEntity->setProfitQuotient(12.5);
        $noSpecEntity->setProfitGrade('A');

        $this->assertEquals(41, $noSpecEntity->getSpecialCape()->getTier());
        $this->assertEquals(41, $noSpecEntity->getDefaultCape()->getTier());
        $this->assertEquals('material', $noSpecEntity->getSecondResource()->getName());
        $this->assertEquals('material', $noSpecEntity->getArtifact()->getName());
        $this->assertEquals(10000, $noSpecEntity->getMaterialCost());
        $this->assertEquals(420, $noSpecEntity->getProfit());
        $this->assertEquals(12.5, $noSpecEntity->getProfitQuotient());
        $this->assertEquals('A', $noSpecEntity->getProfitGrade());
        $this->assertEquals(4, $noSpecEntity->getTierColor());
    }

}